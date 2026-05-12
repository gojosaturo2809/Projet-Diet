<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\HealthModel;
use App\Models\AchatModel;
use App\Models\WalletModel;

class RegimeController extends BaseController
{
    public function index()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to(site_url('login'));
        }

        $regimeModel = new RegimeModel();
        $walletModel = new WalletModel();
        $objectifModel = new ObjectifModel();

        $regimes = $regimeModel->getAllWithComposition();
        $solde = $walletModel->getSoldeUtilisateur($userId);
        $userObjectif = $objectifModel->getObjectifActuel($userId);
        $userWeeks = (int) ($userObjectif['duree_objectif_semaine'] ?? 4);

        $session = session();
        $userName = $session->get('user_nom') ?? '';
        $userPrenom = $session->get('user_prenom') ?? '';
        $initials = substr($userName, 0, 1) . substr($userPrenom, 0, 1);

        $navLinks = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => base_url('dashboard')],
            ['key' => 'regimes', 'label' => 'Regimes', 'href' => base_url('regimes')],
            ['key' => 'wallet', 'label' => 'Portefeuille', 'href' => base_url('wallet')],
        ];

        return view('regimes', [
            'regimesList' => $regimes,
            'regime_weeks' => $userWeeks,
            'solde' => $solde,
            'isGoldUser' => (bool) $session->get('is_gold'),
            'initials' => $initials ?: 'NP',
            'navLinks' => $navLinks,
        ]);
    }

    /**
     * Affiche le détail d'un régime avec ses activités associées
     */
    public function detail()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to(site_url('login'));
        }

        $regimeId = (int) $this->request->getGet('id');
        if ($regimeId <= 0) {
            return redirect()->to(site_url('regimes'))->with('error', 'Régime non spécifié.');
        }

        $regimeModel = new RegimeModel();
        $regime = $regimeModel->getWithActivities($regimeId);

        if (! $regime) {
            return redirect()->to(site_url('regimes'))->with('error', 'Régime introuvable.');
        }

        $session = session();
        $userName = $session->get('user_nom') ?? '';
        $userPrenom = $session->get('user_prenom') ?? '';
        $initials = substr($userName, 0, 1) . substr($userPrenom, 0, 1);

        $navLinks = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => base_url('dashboard')],
            ['key' => 'regimes', 'label' => 'Régimes', 'href' => base_url('regimes')],
            ['key' => 'wallet', 'label' => 'Portefeuille', 'href' => base_url('wallet')],
        ];

        return view('regimes/detail', [
            'regime' => $regime,
            'initials' => $initials ?: 'NP',
            'navLinks' => $navLinks,
            'isGoldUser' => (bool) $session->get('is_gold'),
        ]);
    }

    public function suggestion()
    {
        $idUtilisateur = (int) (session()->get('user_id') ?? 0);

        if ($idUtilisateur <= 0) {
            return redirect()->to(site_url('login'))->with('error', 'Veuillez vous connecter.');
        }

        $objectifModel = new ObjectifModel();
        $regimeModel   = new RegimeModel();
        $healthModel   = new HealthModel();

        $objectif = $objectifModel->getObjectifActuel($idUtilisateur);
        $sante    = $healthModel->where('id_utilisateur', $idUtilisateur)->first();

        if (! $objectif || ! $sante) {
            return redirect()->to(site_url('objectifs'))->with('error', 'Veuillez définir votre profil et objectif d\'abord.');
        }

        $deltaPoids = $objectif['poids_cible'] - $sante['poids'];
        $dureeSemaines = (int) ($objectif['duree_objectif_semaine'] ?? 0);
        if ($dureeSemaines <= 0) {
            return redirect()->to(site_url('objectifs'))->with('error', 'Durée d\'objectif invalide.');
        }

        $imc = null;
        if (! empty($sante['poids']) && ! empty($sante['taille']) && (float) $sante['taille'] > 0) {
            $tailleM = ((float) $sante['taille']) / 100;
            $imc = ((float) $sante['poids']) / ($tailleM * $tailleM);
        }

        $vitesseRequise = $deltaPoids / $dureeSemaines;
        $regimeSuggere = $regimeModel->suggererRegime($vitesseRequise, $imc, (string) ($objectif['nom'] ?? ''));

        if (! $regimeSuggere) {
            return view('regimes/aucun_resultat');
        }

        $joursTotaux = $dureeSemaines * 7;
        $prixTotal   = $regimeSuggere['prix_journalier'] * $joursTotaux;

        $isGold = (bool) (session()->get('is_gold') ?? session()->get('user_gold') ?? false);
        $remise = 0;
        if ($isGold) {
            $remise = $prixTotal * 0.15;
            $prixTotal -= $remise;
        }

        $sport = $regimeModel->getSportSuggere($regimeSuggere['variation_poids_hebdo'] ?? 0);
        $regimeDetail = $regimeModel->getWithActivities((int) ($regimeSuggere['id'] ?? 0)) ?? $regimeSuggere;

        return view('regimes/suggestion_view', [
            'regime' => $regimeDetail,
            'objectif' => $objectif,
            'sante' => $sante,
            'prixTotal' => $prixTotal,
            'remise' => $remise,
            'sport' => $sport,
            'deltaPoids' => abs($deltaPoids),
        ]);
    }

    /**
     * Traitement d'abonnement / achat de régime
     */
    public function souscrire()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to(site_url('login'))->with('error', 'Veuillez vous connecter.');
        }

        $regimeId = (int) $this->request->getPost('regime_id');
        $semaines = max(1, (int) $this->request->getPost('semaines'));

        $regimeModel = new RegimeModel();
        $walletModel = new WalletModel();
        $achatModel = new AchatModel();

        $regime = $regimeModel->find($regimeId);
        if (! $regime) {
            return redirect()->back()->with('error', 'Régime introuvable.');
        }

        $jours = $semaines * 7;
        $prixBase = ((float) $regime['prix_journalier']) * $jours;
        $isGold = (bool) session('is_gold');
        $remise = 0;
        $prixTotal = $prixBase;

        if ($isGold) {
            $remise = $prixBase * 0.15;
            $prixTotal = $prixBase - $remise;
        }

        $solde = $walletModel->getSoldeUtilisateur($userId);
        if ($solde < $prixTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Veuillez recharger votre portefeuille.');
        }

        $achatSuccess = $achatModel->recordAchat($userId, $regimeId, round($prixTotal, 2), round($remise, 2), $semaines);
        if (! $achatSuccess) {
            return redirect()->back()->with('error', 'Impossible d\'enregistrer l\'achat. Veuillez réessayer.');
        }

        $montantStr = number_format($prixTotal, 0, ',', ' ') . ' Ar';
        $msg = 'Achat enregistré en attente de confirmation - ' . $montantStr;
        if ($remise > 0) {
            $msg .= ' (Remise Gold: ' . number_format($remise, 0, ',', ' ') . ' Ar)';
        }

        session()->setFlashdata('success', $msg);
        return redirect()->to(site_url('regimes'));
    }

    /**
     * Vue imprimable pour un régime ou la liste
     */
    public function printable()
    {
        $regimeId = (int) $this->request->getGet('regime_id');
        $weeks = max(1, (int) $this->request->getGet('semaines', 4));

        $regimeModel = new RegimeModel();

        if ($regimeId > 0) {
            $regime = $regimeModel->find($regimeId);
            if (! $regime) {
                return redirect()->back()->with('error', 'Régime introuvable.');
            }
            return view('regimes/printable', ['regimes' => [$regime], 'semaines' => $weeks, 'isGold' => (bool) session('is_gold')]);
        }

        $regimes = $regimeModel->getAllWithComposition();
        return view('regimes/printable', ['regimes' => $regimes, 'semaines' => $weeks, 'isGold' => (bool) session('is_gold')]);
    }
}


