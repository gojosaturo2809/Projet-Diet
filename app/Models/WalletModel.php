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
}