<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table      = 'utilisateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['solde', 'is_gold'];

    public const GOLD_PRICE = 25000.0;

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

    public function activerGold(int $userId, float $prixGold = self::GOLD_PRICE): bool
    {
        $db = $this->db;

        $user = $this->find($userId);
        if (! $user) {
            return false;
        }

        if ((int) ($user['is_gold'] ?? 0) === 1) {
            return true;
        }

        $soldeActuel = (float) ($user['solde'] ?? 0);
        if ($soldeActuel < $prixGold) {
            return false;
        }

        $db->transStart();

        $this->update($userId, [
            'solde' => $soldeActuel - $prixGold,
            'is_gold' => 1,
        ]);

        $db->transComplete();

        return $db->transStatus() === true;
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