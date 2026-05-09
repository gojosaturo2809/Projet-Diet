<?php

namespace App\Controllers;

use App\Models\ObjectifModel;

class ObjectifController extends BaseController
{
    public function index()
    {
        $idUtilisateur = (int) (session()->get('id_utilisateur') ?? 0);

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

    public function selectionner()
    {
        $idUtilisateur = (int) (session()->get('id_utilisateur') ?? 0);

        if ($idUtilisateur <= 0) {
            return redirect()->to(site_url('inscription'))
                ->with('error', 'Votre session a expiré. Recommencez l’inscription.');
        }

        $idObjectif = (int) $this->request->getPost('id_objectif');

        if ($idObjectif <= 0) {
            return redirect()->back()->withInput()
                ->with('error', 'Veuillez choisir un objectif.');
        }

        $model = new ObjectifModel();

        if (! $model->objectifExists($idObjectif)) {
            return redirect()->back()->withInput()
                ->with('error', 'Objectif invalide.');
        }

        if (! $model->saveUserObjective($idUtilisateur, $idObjectif)) {
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue lors de l’enregistrement.');
        }

        $objectif = $model->find($idObjectif);

        session()->set([
            'objectif_id'    => $objectif['id'],
            'objectif_nom'   => $objectif['nom'],
            'inscription_etape' => 'objectif_choisi',
        ]);

        return redirect()->to(site_url('objectifs'))->with('success', 'Objectif enregistré avec succès.');
    }
}