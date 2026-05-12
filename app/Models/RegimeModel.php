<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table            = 'regimes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom', 'description', 'prix_journalier', 'variation_poids_hebdo', 'poids_min_requis'];

    public function getWithComposition(int $id): ?array
    {
        return $this->db->table($this->table)
            ->select('regimes.*, regime_composition.pourcentage_viande, regime_composition.pourcentage_poisson, regime_composition.pourcentage_volaille')
            ->join('regime_composition', 'regimes.id = regime_composition.id_regime', 'left')
            ->where('regimes.id', $id)
            ->get()
            ->getRowArray();
    }

    private function determineGoalDirection(float $vitesseRequise, ?float $imc = null, string $objectifNom = ''): string
    {
        $objectif = mb_strtolower(trim($objectifNom));

        if ($imc !== null && $imc >= 30.0) {
            return 'loss';
        }

        foreach (['obes', 'surpoids', 'maigr', 'perte', 'sèche', 'seche', 'mincir'] as $keyword) {
            if ($objectif !== '' && str_contains($objectif, $keyword)) {
                return 'loss';
            }
        }

        foreach (['prise', 'masse', 'gross', 'muscle', 'gain'] as $keyword) {
            if ($objectif !== '' && str_contains($objectif, $keyword)) {
                return 'gain';
            }
        }

        if ($vitesseRequise < 0) {
            return 'loss';
        }

        if ($vitesseRequise > 0) {
            return 'gain';
        }

        return 'balance';
    }

    private function scoreRegime(array $regime, string $direction, float $vitesseRequise, ?float $imc = null): float
    {
        $variation = (float) ($regime['variation_poids_hebdo'] ?? 0);
        $score = abs($variation - $vitesseRequise);

        if ($direction === 'loss' && $variation > 0) {
            $score += 8.0;
        }

        if ($direction === 'gain' && $variation < 0) {
            $score += 8.0;
        }

        if ($direction === 'balance') {
            $score += abs($variation) * 0.5;
        }

        if ($imc !== null && $imc >= 30.0 && $variation > 0) {
            $score += 12.0;
        }

        $poidsMin = (float) ($regime['poids_min_requis'] ?? 0);
        if ($imc !== null && $poidsMin > 0 && $imc < 18.5 && $direction === 'loss') {
            $score += 3.0;
        }

        return $score;
    }

    /**
     * Algorithme de suggestion de régime
     * @param float $vitesseRequise (kg par semaine, ex: -0.5 pour perdre 2kg en 4 semaines)
     */
    public function suggererRegime(float $vitesseRequise, ?float $imc = null, string $objectifNom = '')
    {
        $direction = $this->determineGoalDirection($vitesseRequise, $imc, $objectifNom);
        $desiredVariation = $vitesseRequise;

        if ($direction === 'loss') {
            $desiredVariation = -abs($vitesseRequise ?: 0.5);
        } elseif ($direction === 'gain') {
            $desiredVariation = abs($vitesseRequise ?: 0.3);
        } else {
            $desiredVariation = 0.0;
        }

        $regimes = $this->getAllWithComposition();
        if (empty($regimes)) {
            return null;
        }

        $filtered = [];
        foreach ($regimes as $regime) {
            $variation = (float) ($regime['variation_poids_hebdo'] ?? 0);

            if ($direction === 'loss' && $variation > 0) {
                continue;
            }

            if ($direction === 'gain' && $variation < 0) {
                continue;
            }

            $filtered[] = $regime;
        }

        if (empty($filtered)) {
            // Si aucun régime ne correspond vraiment à l'objectif, on préfère
            // ne rien proposer plutôt que d'afficher des régimes incompatibles.
            return null;
        }

        usort($filtered, function (array $a, array $b) use ($direction, $desiredVariation, $imc): int {
            $scoreA = $this->scoreRegime($a, $direction, $desiredVariation, $imc);
            $scoreB = $this->scoreRegime($b, $direction, $desiredVariation, $imc);

            if ($scoreA === $scoreB) {
                return strcmp((string) ($a['nom'] ?? ''), (string) ($b['nom'] ?? ''));
            }

            return $scoreA <=> $scoreB;
        });

        return $filtered[0] ?? null;
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

    try {
        $result = $this->db
            ->table('achats_regime')
            ->selectSum('montant_total')
            ->get()
            ->getRow();

        return $result->montant_total ?? 0;
    } catch (\Throwable $e) {
        return 0;
    }
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

    /**
     * Récupère un régime avec ses activités (sports) associées
     */
    public function getWithActivities(int $regimeId): ?array
    {
        // Récupérer le régime avec sa composition
        $regime = $this->getWithComposition($regimeId);
        
        if (! $regime) {
            return null;
        }

        // Récupérer les activités associées à ce régime
        $regime['activites'] = $this->getActivitiesByRegimeId($regimeId);
        $regime['activites_combinations'] = $this->getActivityCombinationsByRegimeId($regimeId);

        return $regime;
    }

    /**
     * Récupère toutes les activités associées à un régime
     */
    public function getActivitiesByRegimeId(int $regimeId): array
    {
        if (! $this->db->tableExists('sports')) {
            return [];
        }

        return $this->db->table('sports')
            ->where('id_regime_associe', $regimeId)
            ->orderBy('intensite', 'ASC')
            ->get()
            ->getResult('array');
    }

    private function buildActivityCombinations(array $activities, int $maxSize, int $startIndex, array $current, array &$results): void
    {
        if (! empty($current)) {
            $results[] = $current;
        }

        if (count($current) >= $maxSize) {
            return;
        }

        $total = count($activities);
        for ($i = $startIndex; $i < $total; $i++) {
            $next = $current;
            $next[] = $activities[$i];
            $this->buildActivityCombinations($activities, $maxSize, $i + 1, $next, $results);
        }
    }

    public function getActivityCombinationsByRegimeId(int $regimeId, int $maxSize = 3): array
    {
        $activities = $this->getActivitiesByRegimeId($regimeId);
        if (empty($activities)) {
            return [];
        }

        $combinations = [];
        $this->buildActivityCombinations($activities, max(1, $maxSize), 0, [], $combinations);

        $ranked = [];
        foreach ($combinations as $combo) {
            $calories = 0;
            $labels = [];
            $intensiteRank = 0;

            foreach ($combo as $activity) {
                $calories += (int) ($activity['calories_heure'] ?? 0);
                $labels[] = (string) ($activity['nom'] ?? '');
                $intensite = strtolower((string) ($activity['intensite'] ?? 'modérée'));
                $intensiteRank += match ($intensite) {
                    'intense' => 3,
                    'modérée', 'moderee' => 2,
                    default => 1,
                };
            }

            $ranked[] = [
                'combinaison' => $combo,
                'label' => implode(' + ', array_filter($labels)),
                'calories_total' => $calories,
                'intensite_score' => $intensiteRank,
                'taille' => count($combo),
            ];
        }

        usort($ranked, function (array $a, array $b): int {
            $scoreA = ($a['taille'] * 10) + $a['intensite_score'] + (int) round($a['calories_total'] / 100);
            $scoreB = ($b['taille'] * 10) + $b['intensite_score'] + (int) round($b['calories_total'] / 100);

            return $scoreB <=> $scoreA;
        });

        return array_slice($ranked, 0, 6);
    }
}