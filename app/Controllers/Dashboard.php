<?php

namespace App\Controllers;

use App\Models\InfoSanteModel;
use App\Models\WalletModel;
use App\Models\ObjectifModel;
use App\Models\RegimeModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $userId = (int) session()->get('user_id');
        $prenom = (string) session()->get('user_prenom');
        $nom = (string) session()->get('user_nom');
        $email = (string) session()->get('user_email');

        $healthModel = new InfoSanteModel();
        $latestHealth = $healthModel->where('id_utilisateur', $userId)->orderBy('id_info', 'DESC')->first();

        $poids = isset($latestHealth['poids']) ? (float) $latestHealth['poids'] : null;
        $taille = isset($latestHealth['taille']) ? (float) $latestHealth['taille'] : null;
        $imc = ($poids !== null && $taille !== null) ? $healthModel->calculerImc($poids, $taille) : null;
        $imcLabel = $imc !== null ? $healthModel->categoriserImc($imc) : 'Renseignez vos données de santé';

        $walletModel = new WalletModel();
        $solde = $walletModel->getSoldeUtilisateur($userId);

        $fullName = trim($prenom . ' ' . $nom);
        $initials = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));

        // Suggestion de régime (dynamique)
        $activeRegime = null;
        $objectifModel = new ObjectifModel();
        $regimeModel = new RegimeModel();

        $objectifActuel = $objectifModel->getObjectifActuel($userId);
        if ($objectifActuel && $latestHealth) {
            $deltaPoids = $objectifActuel['poids_cible'] - $latestHealth['poids'];
            $vitesseRequise = $deltaPoids / max(1, (int) $objectifActuel['duree_objectif_semaine']);
            $sugg = $regimeModel->suggererRegime($vitesseRequise);
            if ($sugg) {
                $totalSemaines = (int) $objectifActuel['duree_objectif_semaine'];
                $activeRegime = [
                    'nom' => $sugg['nom'] ?? '—',
                    'subtitle' => 'Durée : ' . $totalSemaines . ' semaines — ' . (floatval($sugg['variation_poids_hebdo']) < 0 ? 'Réduction de poids' : 'Prise de poids'),
                    'pourcentage_viande' => $sugg['pourcentage_viande'] ?? 0,
                    'pourcentage_poisson' => $sugg['pourcentage_poisson'] ?? 0,
                    'pourcentage_volaille' => $sugg['pourcentage_volaille'] ?? 0,
                    'progress' => 0,
                    'semaine_actuelle' => 1,
                    'total_semaines' => $totalSemaines,
                ];
            }
        }

        // Liste de tous les régimes disponibles (pour que l'utilisateur choisisse)
        $regimesList = $regimeModel->getAllWithComposition();
        // Ajouter un sport suggéré par régime
        foreach ($regimesList as &$rItem) {
            $rItem['suggested_sport'] = $regimeModel->getSportSuggere((float) ($rItem['variation_poids_hebdo'] ?? 0));
        }
        unset($rItem);
        $userWeeks = (int) ($objectifActuel['duree_objectif_semaine'] ?? 4);

        return view('dashboard', [
            'user' => [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'fullName' => $fullName,
            ],
            'solde' => $solde,
            'isGold' => (bool) session('is_gold'),
            'initials' => $initials !== '' ? $initials : 'NP',
            'navLinks' => [
                ['key' => 'dashboard', 'label' => 'Tableau de bord', 'href' => '#top'],
                ['key' => 'regimes', 'label' => 'Régimes', 'href' => '#regimes'],
                ['key' => 'activities', 'label' => 'Activités', 'href' => '#activities'],
                ['key' => 'profil', 'label' => 'Mon Profil', 'href' => '#profil'],
            ],
            'active' => 'dashboard',
            'poids' => $poids,
            'taille' => $taille,
            'imc' => $imc,
            'imcLabel' => $imcLabel,
            'objectifNom' => (string) session('objectif_nom'),
            'objectifId' => (int) (session('objectif_id') ?? 0),
            'badgeGold' => (bool) session('is_gold'),
            'activeRegime' => $activeRegime,
            'regimesList' => $regimesList,
            'regime_weeks' => $userWeeks,
        ]);
    }
}