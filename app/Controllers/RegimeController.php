<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\HealthModel;

class RegimeController extends BaseController
{
    public function suggestion()
    {
        // 1. Récupération de l'utilisateur en session
        $idUtilisateur = (int) (session()->get('id_utilisateur') ?? session()->get('user_id') ?? 0);

        if ($idUtilisateur <= 0) {
            return redirect()->to(site_url('login'))->with('error', 'Veuillez vous connecter.');
        }

        // 2. Instanciation des modèles
        $objectifModel = new ObjectifModel();
        $regimeModel   = new RegimeModel();
        $healthModel   = new HealthModel();

        // 3. Récupération des données nécessaires au calcul
        $objectif = $objectifModel->getObjectifActuel($idUtilisateur);
        $sante    = $healthModel->where('id_utilisateur', $idUtilisateur)->first();

        if (!$objectif || !$sante) {
            return redirect()->to(site_url('objectifs'))->with('error', 'Veuillez définir votre profil et objectif d\'abord.');
        }

        // --- LOGIQUE MATHÉMATIQUE DE SUGGESTION ---

        // Calcul du Delta de poids (ex: 70kg cible - 80kg actuel = -10kg)
        $deltaPoids = $objectif['poids_cible'] - $sante['poids'];
        
        // Calcul de la vitesse requise par semaine (ex: -10kg / 8 semaines = -1.25kg/semaine)
        $vitesseRequise = $deltaPoids / $objectif['duree_objectif_semaine'];

        // 4. Appel de l'algorithme dans le modèle
        $regimeSuggere = $regimeModel->suggererRegime($vitesseRequise);

        if (!$regimeSuggere) {
            return view('regimes/aucun_resultat');
        }

        // 5. Calcul du prix total et des bonus
        $joursTotaux = $objectif['duree_objectif_semaine'] * 7;
        $prixTotal   = $regimeSuggere['prix_journalier'] * $joursTotaux;
        
        // Simulation Remise Gold (si vous avez un champ 'type_compte' dans votre session)
        $remise = 0;
        if (session()->get('user_gold')) {
            $remise = $prixTotal * 0.15; // 15% de réduction
            $prixTotal -= $remise;
        }

        // 6. Récupération du sport adapté à l'intensité
        $sport = $regimeModel->getSportSuggere($regimeSuggere['variation_poids_hebdo']);

        // 7. Envoi à la vue
        return view('regimes/suggestion_view', [
            'regime'      => $regimeSuggere,
            'objectif'    => $objectif,
            'sante'       => $sante,
            'prixTotal'   => $prixTotal,
            'remise'      => $remise,
            'sport'       => $sport,
            'deltaPoids'  => abs($deltaPoids)
        ]);
    }
}