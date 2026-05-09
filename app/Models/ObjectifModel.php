<?php
namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table         = 'objectifs';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nom'];

    public function getAllObjectifs(): array
    {
        return $this->orderBy('id', 'ASC')->findAll();
    }

    public function objectifExists(int $idObjectif): bool
    {
        return $this->find($idObjectif) !== null;
    }

    /**
     * Enregistre l'objectif choisi par l'utilisateur et désactive l'ancien choix actif.
     */
    public function saveUserObjective(int $idUtilisateur, int $idObjectif): bool
    {
        $db = $this->db ?? \Config\Database::connect();

        $db->transStart();

        $db->table('objectifs_utilisateur')
            ->where('id_utilisateur', $idUtilisateur)
            ->update(['actif' => false]);

        $db->table('objectifs_utilisateur')->insert([
            'id_utilisateur' => $idUtilisateur,
            'id_objectif'    => $idObjectif,
            'date_debut'     => date('Y-m-d H:i:s'),
            'actif'          => true,
        ]);

        $db->transComplete();

        return $db->transStatus();
    }

    public function getObjectifActuel(int $idUtilisateur): ?array
    {
        return $this->db->table('objectifs_utilisateur')
            ->select('objectifs_utilisateur.*, objectifs.nom')
            ->join('objectifs', 'objectifs.id = objectifs_utilisateur.id_objectif')
            ->where('objectifs_utilisateur.id_utilisateur', $idUtilisateur)
            ->where('objectifs_utilisateur.actif', true)
            ->orderBy('objectifs_utilisateur.id', 'DESC')
            ->get()
            ->getRowArray();
    }
}