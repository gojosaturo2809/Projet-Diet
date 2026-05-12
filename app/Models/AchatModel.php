<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatModel extends Model
{
    protected $table            = 'achats_regime';
    protected $primaryKey       = 'id_achat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_utilisateur',
        'id_regime',
        'montant_total',
        'duree_semaines',
        'date_achat',
        'statut',
        'date_confirmation',
        'motif_rejet',
    ];
    protected $useTimestamps = false;

    /**
     * Détecte le vrai nom de la colonne ID primaire.
     */
    private function getPrimaryKeyColumn(): string
    {
        static $pkColumn = null;

        if ($pkColumn !== null) {
            return $pkColumn;
        }

        // D'abord utiliser le primaryKey du modèle
        $pkColumn = $this->primaryKey ?? 'id_achat';

        return $pkColumn;
    }

    /**
     * Harmonise une ligne d'achat quelle que soit la version du schéma.
     */
    public function normalizeAchatRow(array $achat): array
    {
        $achat['prix_paye'] = (float) ($achat['prix_paye'] ?? $achat['montant_total'] ?? 0);
        $achat['montant_total'] = (float) ($achat['montant_total'] ?? $achat['prix_paye'] ?? 0);
        $achat['semaines'] = (int) ($achat['semaines'] ?? $achat['duree_semaines'] ?? 0);
        $achat['duree_semaines'] = (int) ($achat['duree_semaines'] ?? $achat['semaines'] ?? 0);

        return $achat;
    }

    /**
     * Trouve un achat par son ID, compatible avec les deux schémas.
     */
    public function findById(int $id): ?array
    {
        $pk = $this->getPrimaryKeyColumn();
        $result = $this->where($pk, $id)->first();
        return $result ? $this->normalizeAchatRow($result) : null;
    }

    /**
     * Retourne les statuts compatibles avec le schéma de la base.
     */
    private function getStatusMap(): array
    {
        static $statusMap = null;

        if ($statusMap !== null) {
            return $statusMap;
        }

        $statusMap = [
            'pending'   => 'en_attente',
            'confirmed' => 'confirmé',
            'rejected'  => 'rejeté',
        ];

        try {
            $row = $this->db->query(
                'SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? LIMIT 1',
                ['achats_regime', 'statut']
            )->getRowArray();

            $columnType = strtolower((string) ($row['COLUMN_TYPE'] ?? ''));

            if (str_starts_with($columnType, 'enum(')) {
                if (str_contains($columnType, "'en_attente'")) {
                    $statusMap = [
                        'pending'   => 'en_attente',
                        'confirmed' => 'confirmé',
                        'rejected'  => 'rejeté',
                    ];
                } else {
                    $statusMap = [
                        'pending'   => 'en_cours',
                        'confirmed' => 'termine',
                        'rejected'  => 'annule',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback silencieux
        }

        return $statusMap;
    }

    /**
     * Enregistrer un achat de régime en attente de confirmation
     */
    public function recordAchat(int $userId, int $regimeId, float $montantTotal, float $remise, int $dureeId): bool
    {
        if (! $this->db->tableExists('achats_regime')) {
            return false;
        }

        $statusMap = $this->getStatusMap();

        return (bool) $this->insert([
            'id_utilisateur'     => $userId,
            'id_regime'          => $regimeId,
            'montant_total'      => $montantTotal,
            'duree_semaines'     => $dureeId,
            'date_achat'         => date('Y-m-d H:i:s'),
            'statut'             => $statusMap['pending'],
        ]);
    }

    /**
     * Récupérer les achats d'un utilisateur
     */
    public function getAchatsUtilisateur(int $userId): array
    {
        $achats = $this->select('achats_regime.*, regimes.nom as regime_nom, regimes.description')
            ->join('regimes', 'regimes.id = achats_regime.id_regime', 'left')
            ->where('achats_regime.id_utilisateur', $userId)
            ->orderBy('achats_regime.date_achat', 'DESC')
            ->findAll();

        return array_map([$this, 'normalizeAchatRow'], $achats);
    }

    /**
     * Récupérer les achats en attente de confirmation (pour admin)
     */
    public function getAchatsEnAttente(): array
    {
        $statusMap = $this->getStatusMap();

        $achats = $this->select('achats_regime.*, regimes.nom as regime_nom, utilisateur.email, utilisateur.nom as user_nom, utilisateur.prenom as user_prenom')
            ->join('regimes', 'regimes.id = achats_regime.id_regime', 'left')
            ->join('utilisateur', 'utilisateur.id = achats_regime.id_utilisateur', 'left')
            ->whereIn('achats_regime.statut', [$statusMap['pending'], 'en_attente', 'en_cours'])
            ->orderBy('achats_regime.date_achat', 'ASC')
            ->findAll();

        return array_map([$this, 'normalizeAchatRow'], $achats);
    }

    /**
     * Confirmer un achat et débiter le portefeuille
     */
    public function confirmAchat(int $achatId): bool
    {
        $db = \Config\Database::connect();
        $achat = $this->findById($achatId);
        $statusMap = $this->getStatusMap();

        if (! $achat || ! in_array((string) ($achat['statut'] ?? ''), ['en_attente', 'en_cours'], true)) {
            return false;
        }

        $montantPaye = (float) ($achat['prix_paye'] ?? $achat['montant_total'] ?? 0);

        $db->transStart();

        // Mettre à jour le statut de l'achat
        $this->update($achatId, [
            'statut'             => $statusMap['confirmed'],
            'date_confirmation'  => date('Y-m-d H:i:s'),
        ]);

        // Débiter le portefeuille de l'utilisateur
        $db->table('utilisateur')
            ->set('solde', 'solde - ' . $montantPaye, false)
            ->where('id', $achat['id_utilisateur'])
            ->update();

        $db->transComplete();

        return $db->transStatus() === true;
    }

    /**
     * Rejeter un achat et créditer le portefeuille si débité
     */
    public function rejectAchat(int $achatId, string $motif = ''): bool
    {
        $db = \Config\Database::connect();
        $achat = $this->findById($achatId);
        $statusMap = $this->getStatusMap();

        if (! $achat || ! in_array((string) ($achat['statut'] ?? ''), ['en_attente', 'en_cours'], true)) {
            return false;
        }

        $montantPaye = (float) ($achat['prix_paye'] ?? $achat['montant_total'] ?? 0);

        $db->transStart();

        $this->update($achatId, [
            'statut'        => $statusMap['rejected'],
            'motif_rejet'   => $motif,
        ]);

        $db->table('utilisateur')
            ->set('solde', 'solde + ' . $montantPaye, false)
            ->where('id', $achat['id_utilisateur'])
            ->update();

        $db->transComplete();

        return $db->transStatus() === true;
    }
}
