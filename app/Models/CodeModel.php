<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeModel extends Model
{
    protected $table      = 'code_recharge';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'montant', 'is_active', 'used_by', 'used_at'];

    public function getAllWithUsers(): array
    {
        return $this->db->table($this->table)
            ->select('code_recharge.*, utilisateur.email, utilisateur.nom, utilisateur.prenom')
            ->join('utilisateur', 'utilisateur.id = code_recharge.used_by', 'left')
            ->orderBy('code_recharge.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function createCode(array $data): bool
    {
        return (bool) $this->insert($data);
    }

    public function getOne(int $id): ?array
    {
        return $this->find($id);
    }

    public function verifierCode(string $code): ?array
    {
        return $this->where('code', $code)
            ->where('is_active', 1)
            ->first();
    }

    public function desactiverCode(int $codeId, int $userId): bool
    {
        return (bool) $this->update($codeId, [
            'is_active' => 0,
            'used_by'   => $userId,
            'used_at'   => date('Y-m-d H:i:s'),
        ]);
    }
}