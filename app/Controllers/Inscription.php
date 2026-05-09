<?php

namespace App\Controllers;

use App\Models\InfoSanteModel;
use App\Models\UtilisateurInscriptionModel;
use Config\Database;

class Inscription extends BaseController
{
    public function index()
    {
        $healthData = session()->get('inscription_health');

        return view('inscription/inscription', [
            'healthData' => is_array($healthData) ? $healthData : [],
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function sante()
    {
        $model = new InfoSanteModel();
        $data = $this->request->getPost(['poids', 'taille']);

        if (! $model->validate($data)) {
            $payload = [
                'status' => 'error',
                'errors' => $model->errors(),
            ];

            return $this->response
                ->setStatusCode(422)
                ->setJSON($payload);
        }

        $poids = (float) $data['poids'];
        $taille = (float) $data['taille'];
        $imc = $model->calculerImc($poids, $taille);
        $categorie = $model->categoriserImc($imc);

        $healthData = [
            'poids'     => $poids,
            'taille'    => $taille,
            'imc'       => $imc,
            'categorie' => $categorie,
        ];

        $model->storeInSession($healthData);
        session()->set('inscription_etape', 'sante');

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Informations de santé enregistrées.',
            'health'    => $healthData,
        ]);
    }

    public function inscription()
    {
        $healthData = session()->get('inscription_health');

        if (! is_array($healthData) || $healthData === []) {
            return redirect()->to(site_url('inscription'))
                ->with('error', 'Commencez par enregistrer vos informations de santé.');
        }

        $compteModel = new UtilisateurInscriptionModel();
        $data = $this->request->getPost();

        if (! $compteModel->validate($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $compteModel->errors());
        }

        $db = Database::connect();
        $db->transBegin();

        $idUtilisateur = $compteModel->createAccount($data);

        if ($idUtilisateur <= 0) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('errors', ['database' => 'Impossible de créer le compte utilisateur.']);
        }

        $infoSanteModel = new InfoSanteModel();

        if (! $infoSanteModel->saveForUser($idUtilisateur, $healthData)) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('errors', ['database' => 'Impossible d\'enregistrer les informations de santé.']);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('errors', ['database' => 'La transaction a échoué.']);
        }

        $db->transCommit();
        $infoSanteModel->clearSession();

        session()->set([
            'id_utilisateur'      => $idUtilisateur,
            'user_id'             => $idUtilisateur,
            'nom'                 => $data['nom'] ?? '',
            'prenom'              => $data['prenom'] ?? '',
            'email'               => $data['email'] ?? '',
            'user_nom'            => $data['nom'] ?? '',
            'user_prenom'         => $data['prenom'] ?? '',
            'user_email'          => $data['email'] ?? '',
            'inscription_etape'   => 'objectifs',
        ]);

        return redirect()->to(site_url('objectifs'))
            ->with('success', 'Compte créé. Choisissez maintenant votre objectif.');
    }
}
