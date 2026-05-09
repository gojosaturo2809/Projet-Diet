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
     * Enregistre l'objectif complet choisi par l'utilisateur.
     */
    public function saveUserObjective(int $idUtilisateur, int $idObjectif, float $poidsCible, int $dureeSemaine): bool
    {
        $db = \Config\Database::connect();

        $db->transStart();

        // 1. Désactiver les anciens objectifs
        $db->table('objectifs_utilisateur')
            ->where('id_utilisateur', $idUtilisateur)
            ->update(['actif' => false]);

        // 2. Insérer le nouvel objectif avec les détails quantifiables
        $db->table('objectifs_utilisateur')->insert([
            'id_utilisateur'         => $idUtilisateur,
            'id_objectif'            => $idObjectif,
            'poids_cible'            => $poidsCible,
            'duree_objectif_semaine' => $dureeSemaine,
            'date_debut'             => date('Y-m-d H:i:s'),
            'actif'                  => true,
        ]);

        $db->transComplete();

        return $db->transStatus();
    }

    /**
     * Récupère l'objectif actuel avec les détails
     */
    public function getObjectifActuel(int $idUtilisateur): ?array
    {
        return $this->db->table('objectifs_utilisateur')
            ->select('objectifs_utilisateur.*, objectifs.nom')
            ->join('objectifs', 'objectifs.id = objectifs_utilisateur.id_objectif')
            ->where('objectifs_utilisateur.id_utilisateur', $idUtilisateur)
            ->where('objectifs_utilisateur.actif', true)
            ->get()
            ->getRowArray();
    }
}