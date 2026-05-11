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
        $userId = (int) (session()->get("user_id") ?? 0);
        if ($userId <= 0) {
            return redirect()->to(site_url("login"));
        }
        
        $regimeModel = new RegimeModel();
        $walletModel = new WalletModel();
        $objectifModel = new ObjectifModel();
        
        $regimes = $regimeModel->getAllWithComposition();
        $solde = $walletModel->getSoldeUtilisateur($userId);
        $userObjectif = $objectifModel->getObjectifActuel($userId);
        $userWeeks = ($userObjectif["duree_objectif_semaine"] ?? 4);
        
        $session = session();
        $userName = $session->get("user_nom") ?? "";
        $userPrenom = $session->get("user_prenom") ?? "";
        $initials = substr($userName, 0, 1) . substr($userPrenom, 0, 1);
        
        $navLinks = [
            ["key" => "dashboard", "label" => "Dashboard", "href" => base_url("dashboard")],
            ["key" => "regimes", "label" => "Regimes", "href" => base_url("regimes")],
            ["key" => "wallet", "label" => "Portefeuille", "href" => base_url("wallet")],
        ];
        
        return view("regimes", [
            "regimesList" => $regimes,
            "regime_weeks" => $userWeeks,
            "solde" => $solde,
            "isGoldUser" => (bool) $session->get("is_gold"),
            "initials" => $initials ?: "NP",
            "navLinks" => $navLinks,
        ]);
    }
      public function suggestion()
    {
        // 1. RÃ©cupÃ©ration de l'utilisateur en session
        $idUtilisateur = (int) (session()->get('id_utilisateur') ?? session()->get('user_id') ?? 0);

        if ($idUtilisateur <= 0) {
            return redirect()->to(site_url('login'))->with('error', 'Veuillez vous connecter.');
        }

        // 2. Instanciation des modÃ¨les
        $objectifModel = new ObjectifModel();
        $regimeModel   = new RegimeModel();
        $healthModel   = new HealthModel();

        // 3. RÃ©cupÃ©ration des donnÃ©es nÃ©cessaires au calcul
        $objectif = $objectifModel->getObjectifActuel($idUtilisateur);
        $sante    = $healthModel->where('id_utilisateur', $idUtilisateur)->first();

        if (!$objectif || !$sante) {
            return redirect()->to(site_url('objectifs'))->with('error', 'Veuillez dÃ©finir votre profil et objectif d\'abord.');
        }

        // --- LOGIQUE MATHÃ‰MATIQUE DE SUGGESTION ---

        // Calcul du Delta de poids (ex: 70kg cible - 80kg actuel = -10kg)
        $deltaPoids = $objectif['poids_cible'] - $sante['poids'];
        
        // Calcul de la vitesse requise par semaine (ex: -10kg / 8 semaines = -1.25kg/semaine)
        $vitesseRequise = $deltaPoids / $objectif['duree_objectif_semaine'];

        // 4. Appel de l'algorithme dans le modÃ¨le
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
            $remise = $prixTotal * 0.15; // 15% de rÃ©duction
            $prixTotal -= $remise;
        }

        // 6. RÃ©cupÃ©ration du sport adaptÃ© Ã  l'intensitÃ©
        $sport = $regimeModel->getSportSuggere($regimeSuggere['variation_poids_hebdo']);

        // 7. Envoi Ã  la vue
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

    /**
     * Traitement d'abonnement / achat de rÃ©gime
     * L'achat est enregistrÃ© en attente de confirmation par l'admin
     * Le solde n'est dÃ©bitÃ© que lors de la confirmation
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
        if (!$regime) {
            return redirect()->back()->with('error', 'RÃ©gime introuvable.');
        }

        $jours = $semaines * 7;
        $prixBase = ((float) $regime['prix_journalier']) * $jours;
        $isGold = (bool) session('is_gold');
        $remise = 0;
        $prixTotal = $prixBase;
        
        if ($isGold) {
            $remise = $prixBase * 0.15; // 15% de rÃ©duction
            $prixTotal = $prixBase - $remise;
        }

        $solde = $walletModel->getSoldeUtilisateur($userId);
        if ($solde < $prixTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Veuillez recharger votre portefeuille.');
        }

        // Enregistrer l'achat EN ATTENTE DE CONFIRMATION (pas de dÃ©bit immÃ©diat)
        $achatSuccess = $achatModel->recordAchat($userId, $regimeId, round($prixTotal, 2), round($remise, 2), $semaines);

        if (!$achatSuccess) {
            return redirect()->back()->with('error', 'Impossible d\'enregistrer l\'achat. Veuillez rÃ©essayer.');
        }

        // Message de succÃ¨s - achat en attente de confirmation
        $montantStr = number_format($prixTotal, 0, ',', ' ') . ' Ar';
        $msg = 'Achat enregistrÃ© en attente de confirmation - ' . $montantStr;
        if ($remise > 0) {
            $msg .= ' (Remise Gold: ' . number_format($remise, 0, ',', ' ') . ' Ar)';
        }

        session()->setFlashdata('success', $msg);
        return redirect()->to(site_url('regimes'));
    }

    /**
     * Vue imprimable pour un rÃ©gime ou la liste (utiliser Print->Save as PDF)
     */
    public function printable()
    {
        $regimeId = (int) $this->request->getGet('regime_id');
        $weeks = max(1, (int) $this->request->getGet('semaines', 4));

        $regimeModel = new RegimeModel();

        if ($regimeId > 0) {
            $regime = $regimeModel->find($regimeId);
            if (! $regime) {
                return redirect()->back()->with('error', 'RÃ©gime introuvable.');
            }
            return view('regimes/printable', ['regimes' => [$regime], 'semaines' => $weeks, 'isGold' => (bool) session('is_gold')]);
        }

        $regimes = $regimeModel->getAllWithComposition();
        return view('regimes/printable', ['regimes' => $regimes, 'semaines' => $weeks, 'isGold' => (bool) session('is_gold')]);
    }

    /**
     * TÃ©lÃ©charger le PDF d'un rÃ©gime ou la liste complÃ¨te
     * GÃ©nÃ¨re un fichier PDF optimisÃ© (HTML+CSS pour navigateur)
     */
    public function downloadPdf()
    {
        try {
            $regimeId = (int) $this->request->getGet('regime_id');
            $weeks = max(1, (int) $this->request->getGet('semaines', 4));
            $regimeModel = new RegimeModel();

            if ($regimeId > 0) {
                $regime = $regimeModel->find($regimeId);
                if (!$regime) {
                    return redirect()->back()->with('error', 'RÃ©gime introuvable.');
                }
                $regimes = [$regime];
            } else {
                $regimes = $regimeModel->getAllWithComposition();
            }

            // RÃ©cupÃ©rer le statut gold (avec fallback si session non disponible)
            $isGold = false;
            try {
                $isGold = (bool) session('is_gold');
            } catch (\Exception $e) {
                $isGold = false;
            }

            // GÃ©nÃ©rer le contenu HTML optimisÃ© pour PDF
            $html = $this->generatePdfHtml($regimes, $weeks, $isGold);

            // Retourner comme rÃ©ponse avec header de tÃ©lÃ©chargement
            $filename = 'regimes-nutriplan-' . date('Y-m-d-His') . '.html';
            return $this->response
                ->setContentType('text/html; charset=utf-8')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($html);
        } catch (\Exception $e) {
            log_message('error', 'Error in downloadPdf: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la gÃ©nÃ©ration du PDF.');
        }
    }

    /**
     * Helper: GÃ©nÃ©rer le HTML optimisÃ© pour impression PDF
     */
    private function generatePdfHtml(array $regimes, int $weeks, bool $isGold = false): string
    {
        $html = '<!DOCTYPE html><html lang="fr"><head>';
        $html .= '<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
        $html .= '<title>NutriPlan - Export RÃ©gimes</title>';
        $html .= '<style>
            body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; color: #1a1a1a; line-height: 1.6; margin: 0; padding: 20px; }
            @page { margin: 20mm; size: A4; }
            @media print { body { background: white; } .no-print { display: none; } }
            .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #d32f2f; padding-bottom: 15px; }
            .header h1 { margin: 0; color: #d32f2f; font-size: 28px; }
            .header p { margin: 5px 0 0; color: #666; font-size: 13px; }
            .regime-card { break-inside: avoid; page-break-inside: avoid; background: #f9f9f9; border: 1px solid #ddd; border-radius: 6px; padding: 20px; margin-bottom: 20px; }
            .regime-card h2 { margin: 0 0 10px; color: #333; font-size: 20px; }
            .regime-card p { margin: 8px 0; color: #555; font-size: 14px; }
            .composition { display: flex; gap: 20px; margin: 15px 0; flex-wrap: wrap; }
            .comp-item { flex: 1; min-width: 120px; }
            .comp-label { font-size: 12px; color: #999; font-weight: 600; }
            .comp-value { font-size: 18px; font-weight: bold; color: #333; }
            .pricing { margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; }
            .price-row { display: flex; justify-content: space-between; margin: 5px 0; font-size: 14px; }
            .price-label { color: #666; }
            .price-value { font-weight: bold; color: #333; }
            .discount { color: #d32f2f; }
            .no-print { margin: 20px 0; text-align: center; }
            .no-print button { padding: 10px 20px; font-size: 14px; background: #d32f2f; color: white; border: none; border-radius: 4px; cursor: pointer; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #999; }
        </style>';
        $html .= '</head><body>';

        $html .= '<div class="header">';
        $html .= '<h1>NutriPlan</h1>';
        $html .= '<p>Programme de rÃ©gimes personnalisÃ©s â€“ Export du ' . date('d/m/Y H:i') . '</p>';
        $html .= '</div>';

        $html .= '<div class="no-print"><button onclick="window.print()">Imprimer / Enregistrer en PDF</button></div>';

        foreach ($regimes as $r) {
            $prixJour = (float) ($r['prix_journalier'] ?? 0);
            $jours = $weeks * 7;
            $prixBase = $prixJour * $jours;
            $remise = $isGold ? $prixBase * 0.15 : 0;
            $prixFinal = $prixBase - $remise;

            $html .= '<div class="regime-card">';
            $html .= '<h2>' . htmlspecialchars($r['nom'] ?? 'â€“') . '</h2>';
            $html .= '<p>' . htmlspecialchars($r['description'] ?? '') . '</p>';

            $html .= '<div class="composition">';
            $html .= '<div class="comp-item"><div class="comp-label">Viande</div><div class="comp-value">' . (int) ($r['pourcentage_viande'] ?? 0) . '%</div></div>';
            $html .= '<div class="comp-item"><div class="comp-label">Poisson</div><div class="comp-value">' . (int) ($r['pourcentage_poisson'] ?? 0) . '%</div></div>';
            $html .= '<div class="comp-item"><div class="comp-label">Volaille</div><div class="comp-value">' . (int) ($r['pourcentage_volaille'] ?? 0) . '%</div></div>';
            $html .= '</div>';

            $html .= '<div class="pricing">';
            $html .= '<div class="price-row"><span class="price-label">Prix journalier</span><span class="price-value">' . number_format($prixJour, 0, ',', ' ') . ' Ar</span></div>';
            $html .= '<div class="price-row"><span class="price-label">DurÃ©e</span><span class="price-value">' . (int) $weeks . ' semaines</span></div>';
            $html .= '<div class="price-row"><span class="price-label">Prix total (base)</span><span class="price-value">' . number_format($prixBase, 0, ',', ' ') . ' Ar</span></div>';
            if ($remise > 0) {
                $html .= '<div class="price-row discount"><span class="price-label">Remise Gold (15%)</span><span class="price-value">-' . number_format($remise, 0, ',', ' ') . ' Ar</span></div>';
            }
            $html .= '<div class="price-row" style="font-size:16px;font-weight:bold;margin-top:10px"><span class="price-label">TOTAL</span><span class="price-value">' . number_format($prixFinal, 0, ',', ' ') . ' Ar</span></div>';
            $html .= '</div>';

            $html .= '</div>';
        }

        $html .= '<div class="footer">NutriPlan â€“ Votre santÃ©, notre prioritÃ©</div>';
        $html .= '</body></html>';

        return $html;
    }
}


