<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
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
        $user = $this->where('email', $email)->first();

        if (! $user) {
            return false;
        }

        if (password_verify((string) $password, (string) $user['mot_de_passe'])) {
            return $user;
        }

        if ((string) $user['mot_de_passe'] === (string) $password) {
            return $user;
        }

        return false;
    }
    public function countUsers()
{
    return $this->countAllResults();
}
}
