<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table      = 'utilisateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['solde', 'is_gold'];

    public function getSoldeUtilisateur(int $userId): float
    {
        $row = $this->select('solde')->find($userId);

        if (!$row) {
            return 0;
        }

        return (float) ($row['solde'] ?? 0);
    }

    public function modifierSolde(int $userId, float $montant): bool
    {
        $current = $this->getSoldeUtilisateur($userId);
        $newSolde = $current + $montant;

        return (bool) $this->update($userId, ['solde' => $newSolde]);
    }

    public function activerGold(int $userId): bool
    {
        return (bool) $this->update($userId, ['is_gold' => 1]);
    }
    public function countCodes()
{
    if (! $this->db->tableExists('code_recharge')) {
        return 0;
    }

    return $this->db
        ->table('code_recharge')
        ->countAllResults();
}
public function getAllRechargeCodes()
{
    if (! $this->db->tableExists('code_recharge')) {
        return [];
    }

    return $this->db
        ->table('code_recharge')
        ->select('code_recharge.*, utilisateur.email')

        ->join(
            'utilisateur',
            'utilisateur.id = code_recharge.used_by',
            'left'
        )

        ->orderBy('id', 'DESC')

        ->get()

        ->getResultArray();
}
public function createRechargeCode($data)
{
    return $this->db
        ->table('code_recharge')
        ->insert($data);
}
}