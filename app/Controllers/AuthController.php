<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HealthModel;

class AuthController extends BaseController
{
    public function registerStep1()
    {
        helper('text');

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'nom' => $this->request->getPost('nom'),
                'prenom' => $this->request->getPost('prenom'),
                'email' => $this->request->getPost('email'),
                'mot_de_passe' => $this->request->getPost('mot_de_passe'),
            ];

            $userModel = new UserModel();

            // Validate with model rules
            if (! $userModel->validate($data)) {
                $errors = $userModel->errors();
                return redirect()->back()->withInput()->with('errors', $errors);
            }

            // Hash password before storing in session
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);

            session()->set('reg_step1', $data);

            return redirect()->to(site_url('register/step2'));
        }

        return view('register/register_step1');
    }

    public function registerStep2()
    {
        if ($this->request->getMethod() === 'post') {
            $reg = session()->get('reg_step1');

            if (! $reg) {
        if ($this->request->getMethod() === 'POST') {
                    ->with('errors', ["Vous devez d'abord remplir les informations personnelles."]);
            }

            $poids = $this->request->getPost('poids');
            $taille = $this->request->getPost('taille');

            $healthModel = new HealthModel();
            if (! $healthModel->validate(['poids' => $poids, 'taille' => $taille])) {
                $errors = $healthModel->errors();
                return redirect()->back()->withInput()->with('errors', $errors);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $userModel = new UserModel();
            $userData = [
                'nom' => $reg['nom'],
                'prenom' => $reg['prenom'],
                'email' => $reg['email'],
                'mot_de_passe' => $reg['mot_de_passe'],
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $userModel->insert($userData);
            $id = $userModel->getInsertID();

            $healthModel->insert([
                'id_utilisateur' => $id,
                'poids' => $poids,
                'taille' => $taille,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['Erreur lors de l\'enregistrement.']);
            }

            session()->remove('reg_step1');
            session()->setFlashdata('success', 'Inscription réussie. Vous pouvez vous connecter.');

            return redirect()->to(site_url('login'));
        }

        return view('register/register_step2');
    }
}
