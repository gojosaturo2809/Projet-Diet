<?php

namespace App\Models;

use CodeIgniter\Model;

class InfoSanteModel extends Model
{
    protected $table = 'info_sante';
    protected $primaryKey = 'id_info';
    protected $allowedFields = ['id_utilisateur', 'poids', 'taille'];

    protected $validationRules = [
        'poids' => 'required|numeric|greater_than[0]',
        'taille' => 'required|numeric|greater_than[0]',
    ];

    protected $validationMessages = [
        'poids' => [
            'required' => 'Le poids est requis',
            'numeric' => 'Le poids doit être un nombre',
            'greater_than' => 'Le poids doit être supérieur à 0',
        ],
        'taille' => [
            'required' => 'La taille est requise',
            'numeric' => 'La taille doit être un nombre',
            'greater_than' => 'La taille doit être supérieure à 0',
        ],
    ];

    public function saveForUser(int $idUtilisateur, array $healthData): bool
    {
        return (bool) $this->insert([
            'id_utilisateur' => $idUtilisateur,
            'poids'          => (float) $healthData['poids'],
            'taille'         => (float) $healthData['taille'],
        ]);
    }

    public function calculerImc(float $poids, float $tailleCm): float
    {
        $tailleMetres = $tailleCm / 100;

        if ($tailleMetres <= 0) {
            return 0.0;
        }

        return round($poids / ($tailleMetres * $tailleMetres), 1);
    }

    public function categoriserImc(float $imc): string
    {
        if ($imc < 18.5) {
            return 'Insuffisance pondérale';
        }

        if ($imc < 25) {
            return 'Poids normal';
        }

        if ($imc < 30) {
            return 'Surpoids';
        }

        return 'Obésité';
    }

    public function storeInSession(array $healthData): void
    {
        session()->set('inscription_health', $healthData);
    }

    public function clearSession(): void
    {
        session()->remove('inscription_health');
    }
}
