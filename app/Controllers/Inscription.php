<?php

namespace App\Controllers;

use App\Models\InscrptionModele;

class Inscription extends BaseController
{
    public function inscription()
    {
        try {
            $modele = new InscrptionModele();
            $data = $this->request->getPost();

            if (! $modele->insert($data)) {
                $errors = $modele->errors();

                return redirect()->back()
                    ->withInput()
                    ->with('errors', $errors);
            }

            $idUtilisateur = (int) $modele->getInsertID();

            session()->set([
                'id_utilisateur'    => $idUtilisateur,
                'nom'               => $data['nom'] ?? '',
                'prenom'            => $data['prenom'] ?? '',
                'email'             => $data['email'] ?? '',
                'inscription_etape' => 'objectifs',
            ]);

            return redirect()->to(site_url('objectifs'))
                ->with('success', 'Compte créé. Choisissez maintenant votre objectif.');
        } catch (\Exception $e) {
            return 'Erreur : ' . $e->getMessage();
        }
    }

    public function index()
    {
        return view('inscription/inscription');
    }
}
