<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\InfoSanteModel;

class ObjectifController extends BaseController
{
    public function index()
    {
        $idUtilisateur = (int) (session()->get('id_utilisateur') ?? session()->get('user_id') ?? 0);

        if ($idUtilisateur <= 0) {
            return redirect()->to(site_url('inscription'))
                ->with('error', 'Veuillez terminer votre inscription avant de choisir un objectif.');
        }

        $model = new ObjectifModel();

        return view('objectifs/Objectif', [
            'objectifs'      => $model->getAllObjectifs(),
            'objectifActuel' => $model->getObjectifActuel($idUtilisateur),
        ]);
    }

    /**
     * Méthode appelée par AJAX pour afficher le formulaire de détails
     */
    public function getDetailsForm()
    {
        $idObjectif = (int) $this->request->getGet('id_objectif');
        $idUtilisateur = (int) (session()->get('id_utilisateur') ?? session()->get('user_id') ?? 0);

        if ($idObjectif <= 0 || $idUtilisateur <= 0) {
            return view('objectifs/partials/_form_details', [
                'error' => 'Données utilisateur ou objectif manquantes.',
            ]);
        }

        $objectifModel = new ObjectifModel();
        $objectif = $objectifModel->find($idObjectif);

        if (! is_array($objectif)) {
            return view('objectifs/partials/_form_details', [
                'error' => 'Objectif invalide.',
            ]);
        }

        $healthModel = new InfoSanteModel();
        $sante = $healthModel->where('id_utilisateur', $idUtilisateur)->first();

        if (! is_array($sante)) {
            return view('objectifs/partials/_form_details', [
                'error' => 'Informations de santé introuvables. Reprenez l’inscription.',
            ]);
        }

        $tailleMetres = ((float) $sante['taille']) / 100;
        $poidsIdeal = $tailleMetres > 0 ? round(22 * ($tailleMetres * $tailleMetres), 1) : 0.0;

        return view('objectifs/partials/_form_details', [
            'idObjectif'  => $idObjectif,
            'objectif'    => $objectif,
            'poidsIdeal'  => $poidsIdeal,
            'poidsActuel' => (float) $sante['poids'],
            'taille'      => (float) $sante['taille'],
        ]);
    }

    public function selectionner()
    {
        $idUtilisateur = (int) (session()->get('id_utilisateur') ?? session()->get('user_id') ?? 0);
        if ($idUtilisateur <= 0) {
            return redirect()->to(site_url('inscription'))
                ->with('error', 'Votre session a expiré. Recommencez l’inscription.');
        }

        $model = new ObjectifModel();

        $idObjectif = (int) $this->request->getPost('id_objectif');
        $poidsCible = (float) $this->request->getPost('poids_cible');
        $duree = (int) $this->request->getPost('duree_objectif_semaine');

        if ($idObjectif <= 0) {
            return redirect()->back()->withInput()
                ->with('error', 'Veuillez choisir un objectif.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_objectif'  => 'required|is_not_unique[objectifs.id]',
            'poids_cible'  => 'required|numeric|greater_than[0]',
            'duree_objectif_semaine' => 'required|integer|greater_than[0]'
        ]);

        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $healthModel = new InfoSanteModel();
        $sante = $healthModel->where('id_utilisateur', $idUtilisateur)->first();

        if (! is_array($sante) || empty($sante['taille'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Informations de santé introuvables.');
        }

        $tailleMetres = ((float) $sante['taille']) / 100;
        if ($tailleMetres <= 0) {
            return redirect()->back()->withInput()
                ->with('error', 'La taille enregistrée est invalide.');
        }

        $imcPrevu = $poidsCible / ($tailleMetres * $tailleMetres);
        $warning = '';

        if ($imcPrevu < 18.5 && $idObjectif === 1) {
            $warning = 'Attention : Ce poids cible est très bas (IMC < 18.5). Soyez prudent.';
        }

        if (! $model->saveUserObjective($idUtilisateur, $idObjectif, $poidsCible, $duree)) {
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur lors de l enregistrement.');
        }

        $msg = 'Objectif enregistre avec succes.';
        if ($warning) {
            $msg .= ' ' . $warning;
        }

        return redirect()->to(site_url('dashboard'))->with('success', $msg);
    }
}