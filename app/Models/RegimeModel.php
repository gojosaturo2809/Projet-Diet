<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table            = 'regimes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom', 'description', 'prix_journalier', 'variation_poids_hebdo', 'poids_min_requis'];

    /**
     * Algorithme de suggestion de régime
     * @param float $vitesseRequise (kg par semaine, ex: -0.5 pour perdre 2kg en 4 semaines)
     */
    public function suggererRegime(float $vitesseRequise)
    {
        // On cherche le régime dont la variation hebdomadaire est la plus proche 
        // de la vitesse requise par l'utilisateur.
        return $this->db->table($this->table)
            ->select('regimes.*, regime_composition.*')
            ->join('regime_composition', 'regimes.id = regime_composition.id_regime')
            // Tri par la différence absolue la plus petite entre l'objectif et la capacité du régime
            ->orderBy("ABS(variation_poids_hebdo - $vitesseRequise)", 'ASC')
            ->get()
            ->getRowArray();
    }
    public function countRegimes()
{
    if (! $this->db->tableExists('regimes')) {
        return 0;
    }

    return $this->db
        ->table('regimes')
        ->countAllResults();
}
public function getTotalRevenue()
{
    if (! $this->db->tableExists('achats_regime')) {
        return 0;
    }

    $result = $this->db
        ->table('achats_regime')
        ->selectSum('montant_total')
        ->get()
        ->getRow();

    return $result->montant_total ?? 0;
}

    /**
     * Récupère le sport associé à l'intensité du régime choisi
     */
    public function getSportSuggere(float $variationHebdo)
    {
        // Si le régime est intense (perte/gain > 0.8kg/sem), on suggère un sport intense
        $intensite = 'Modérée';
        if (abs($variationHebdo) >= 0.8) {
            $intensite = 'Intense';
        } elseif (abs($variationHebdo) < 0.4) {
            $intensite = 'Faible';
        }

        return $this->db->table('sports')
            ->where('intensite', $intensite)
            ->get()
            ->getRowArray();
    }

    /**
     * Retourne tous les régimes (optionnellement filtrés par critères simples)
     * Ici on renvoie la composition jointe si présente.
     */
    public function getAllWithComposition(): array
    {
        if (! $this->db->tableExists('regimes')) {
            return [];
        }

        if (! $this->db->tableExists('regime_composition')) {
            return $this->db->table($this->table)
                ->select('regimes.*')
                ->orderBy('regimes.id', 'ASC')
                ->get()
                ->getResult('array');
        }

        return $this->db->table($this->table)
            ->select('regimes.*, regime_composition.pourcentage_viande, regime_composition.pourcentage_poisson, regime_composition.pourcentage_volaille')
            ->join('regime_composition', 'regimes.id = regime_composition.id_regime', 'left')
            ->orderBy('regimes.id', 'ASC')
            ->get()
            ->getResult('array');
    }
}