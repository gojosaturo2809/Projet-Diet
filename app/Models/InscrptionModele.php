<?php
namespace App\Models;

use CodeIgniter\Model;

class InscrptionModele extends Model{

    protected $table = 'utilisateur';

    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'prenom', 'email', 'mot_de_passe'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[50]',
        'prenom' => 'required|min_length[2]|max_length[50]',
        'email' => 'required|valid_email|is_unique[utilisateur.email]',
        'mot_de_passe' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom est requis',
            'min_length' => 'Le nom doit comporter au moins 2 caractères',
            'max_length' => 'Le nom ne doit pas dépasser 50 caractères',
        ],
        'prenom'=> [
            'required' => 'Le prénom est requis',
            'min_length' => 'Le prénom doit comporter au moins 2 caractères',
            'max_length' => 'Le prénom ne doit pas dépasser 50 caractères',
        ],
        'email'=> [
            'required' => 'L\'email est requis',
            'valid_email' => 'L\'email doit être valide',
            'is_unique' => 'Cet email est déjà utilisé',
        ],
        'mot_de_passe' => [
            'required' => 'Le mot de passe est requis',
            'min_length' => 'Le mot de passe doit comporter au moins 6 caractères',
        ],
    ];

}

