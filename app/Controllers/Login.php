<?php

namespace App\Controllers;

class Login extends BaseController{

public function index(){
    return view('login/login');
}

public function login(){
    try {
        $model = new \App\Models\LoginModel();
        $data = $this->request->getPost();

        // Authentification
        $user = $model->authenticate($data['email'] ?? '', $data['mot_de_passe'] ?? '');
        
        if ($user) {
            // Connexion réussie - créer une session
            session()->set('user_id', $user['id']);
            session()->set('user_email', $user['email']);
            session()->set('user_nom', $user['nom']);
            session()->set('user_prenom', $user['prenom']);

            // Redirection vers le dashboard
            return redirect()->to('/dashboard')->with('success', 'Connexion réussie !');
        } else {
            // Échec d'authentification
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors de la connexion');
    }
}

public function logout(){
    session()->destroy();
    return redirect()->to('/login')->with('success', 'Déconnexion réussie');
}
}
