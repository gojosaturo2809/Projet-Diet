<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nom', 'prenom', 'email', 'mot_de_passe', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[50]',
        'prenom' => 'required|min_length[2]|max_length[50]',
        'email' => 'required|valid_email',
        'mot_de_passe' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom est requis.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
            'max_length' => 'Le nom ne peut pas dépasser 50 caractères.',
        ],
        'prenom' => [
            'required' => 'Le prénom est requis.',
            'min_length' => 'Le prénom doit contenir au moins 2 caractères.',
            'max_length' => 'Le prénom ne peut pas dépasser 50 caractères.',
        ],
        'email' => [
            'required' => 'L\'email est requis.',
            'valid_email' => 'L\'email fourni n\'est pas valide.',
        ],
        'mot_de_passe' => [
            'required' => 'Le mot de passe est requis.',
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ],
    ];
}
