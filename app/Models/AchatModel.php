<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatModel extends Model
{
    protected $table            = 'achats_regime';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_utilisateur',
        'id_regime',
        'prix_paye',
        'remise_appliquee',
        'semaines',
        'date_achat',
    ];
    protected $useTimestamps = false;

    /**
     * Enregistrer un achat de régime
     */
    public function recordAchat(int $userId, int $regimeId, float $prixPaye, float $remise, int $semaines): bool
    {
        // Check if the table exists before attempting to insert
        if (! $this->db->tableExists('achats_regime')) {
            return false;
        }

        return (bool) $this->insert([
            'id_utilisateur'     => $userId,
            'id_regime'          => $regimeId,
            'prix_paye'          => $prixPaye,
            'remise_appliquee'   => $remise,
            'semaines'           => $semaines,
            'date_achat'         => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Récupérer les achats d'un utilisateur
     */
    public function getAchatsUtilisateur(int $userId): array
    {
        return $this->select('achats_regime.*, regimes.nom as regime_nom, regimes.description')
            ->join('regimes', 'regimes.id = achats_regime.id_regime', 'left')
            ->where('achats_regime.id_utilisateur', $userId)
            ->orderBy('achats_regime.date_achat', 'DESC')
            ->findAll();
    }
}
