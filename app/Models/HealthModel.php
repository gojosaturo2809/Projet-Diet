<?php

namespace App\Models;

use CodeIgniter\Model;

class HealthModel extends Model
{
    protected $table = 'info_sante';
    protected $primaryKey = 'id_info';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_utilisateur', 'poids', 'taille'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'poids' => 'required|numeric',
        'taille' => 'required|numeric',
    ];

    protected $validationMessages = [
        'poids' => [
            'required' => 'Le poids est requis.',
            'numeric' => 'Le poids doit être un nombre.',
        ],
        'taille' => [
            'required' => 'La taille est requise.',
            'numeric' => 'La taille doit être un nombre.',
        ],
    ];
}
