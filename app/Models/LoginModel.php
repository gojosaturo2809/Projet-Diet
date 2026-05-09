<?php 
namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model{

    protected $table = 'utilisateur';

    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'mot_de_passe'];

    protected $validationRules = [
        'email' => 'required|valid_email',
        'mot_de_passe' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'L\'email est requis',
            'valid_email' => 'L\'email doit être valide',
        ],
        'mot_de_passe' => [
            'required' => 'Le mot de passe est requis',
            'min_length' => 'Le mot de passe doit comporter au moins 6 caractères',
        ],
    ];


    public function authenticate($email, $password)
    {
        // Recherche l'utilisateur par email
        $user = $this->where('email', $email)->first();
        
        if (!$user) {
            return false; // Email n'existe pas
        }

        // Vérifie le mot de passe (supposant qu'il n'est pas hashé pour l'instant)
        if ($user['mot_de_passe'] === $password) {
            return $user; // Retourne les données utilisateur
        }

        return false; // Mot de passe incorrect
    }
}