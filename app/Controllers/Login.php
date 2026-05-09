<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        return view('login/login');
    }

    public function login()
    {
        try {
            $model = new \App\Models\LoginModel();
            $data = $this->request->getPost();

            $user = $model->authenticate($data['email'] ?? '', $data['mot_de_passe'] ?? '');

            if ($user) {
                session()->set([
                    'user_id'         => $user['id'],
                    'id_utilisateur'  => $user['id'],
                    'user_email'      => $user['email'],
                    'user_nom'        => $user['nom'],
                    'user_prenom'     => $user['prenom'],
                    'email'           => $user['email'],
                    'nom'             => $user['nom'],
                    'prenom'          => $user['prenom'],
                ]);

                return redirect()->to('/dashboard')->with('success', 'Connexion réussie !');
            }

            return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la connexion');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Déconnexion réussie');
    }
}
