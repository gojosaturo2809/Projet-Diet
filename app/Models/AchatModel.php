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
     * Enregistrer un achat de rÃ©gime en attente de confirmation
     */
    public function recordAchat(int $userId, int $regimeId, float $montantTotal, float $remise, int $dureeId): bool
    {
        // Check if the table exists before attempting to insert
        if (! $this->db->tableExists('achats_regime')) {
            return false;
        }

        return (bool) $this->insert([
            'id_utilisateur'     => $userId,
            'id_regime'          => $regimeId,
            'montant_total'      => $montantTotal,
            'duree_semaines'     => $dureeId,
            'date_achat'         => date('Y-m-d H:i:s'),
            'statut'             => 'en_attente',
        ]);
    }

    /**
     * RÃ©cupÃ©rer les achats d'un utilisateur
     */
    public function getAchatsUtilisateur(int $userId): array
    {
        return $this->select('achats_regime.*, regimes.nom as regime_nom, regimes.description')
            ->join('regimes', 'regimes.id = achats_regime.id_regime', 'left')
            ->where('achats_regime.id_utilisateur', $userId)
            ->orderBy('achats_regime.date_achat', 'DESC')
            ->findAll();
    }

    /**
     * RÃ©cupÃ©rer les achats en attente de confirmation (pour admin)
     */
    public function getAchatsEnAttente(): array
    {
        return $this->select('achats_regime.*, regimes.nom as regime_nom, utilisateur.email, utilisateur.nom as user_nom, utilisateur.prenom as user_prenom')
            ->join('regimes', 'regimes.id = achats_regime.id_regime', 'left')
            ->join('utilisateur', 'utilisateur.id = achats_regime.id_utilisateur', 'left')
            ->where('achats_regime.statut', 'en_attente')
            ->orderBy('achats_regime.date_achat', 'ASC')
            ->findAll();
    }

    /**
     * Confirmer un achat et dÃ©biter le portefeuille
     */
    public function confirmAchat(int $achatId): bool
    {
        $db = \Config\Database::connect();
        $achat = $this->find($achatId);

        if (!$achat || $achat['statut'] !== 'en_attente') {
            return false;
        }

        $db->transStart();

        // Mettre Ã  jour le statut de l'achat
        $this->update($achatId, [
            'statut'             => 'confirmÃ©',
            'date_confirmation'  => date('Y-m-d H:i:s'),
        ]);

        // DÃ©biter le portefeuille de l'utilisateur
        $db->table('utilisateur')
            ->set('solde', 'solde - ' . (float) $achat['prix_paye'], false)
            ->where('id', $achat['id_utilisateur'])
            ->update();

        $db->transComplete();

        return $db->transStatus() === true;
    }

    /**
     * Rejeter un achat et crÃ©diter le portefeuille si dÃ©bitÃ©
     */
    public function rejectAchat(int $achatId, string $motif = ''): bool
    {
        $db = \Config\Database::connect();
        $achat = $this->find($achatId);

        if (!$achat || $achat['statut'] !== 'en_attente') {
            return false;
        }

        $db->transStart();

        // Mettre Ã  jour le statut de l'achat
        $this->update($achatId, [
            'statut'        => 'rejetÃ©',
            'motif_rejet'   => $motif,
        ]);

        // CrÃ©diter le portefeuille de l'utilisateur (remboursement)
        $db->table('utilisateur')
            ->set('solde', 'solde + ' . (float) $achat['prix_paye'], false)
            ->where('id', $achat['id_utilisateur'])
            ->update();

        $db->transComplete();

        return $db->transStatus() === true;
    }
}


