<?php

namespace App\Controllers;

class Dashboard extends BaseController{

    public function index(){
        // Vérifier si l'utilisateur est connecté
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $data = [
            'user' => [
                'nom' => session()->get('user_nom'),
                'prenom' => session()->get('user_prenom'),
                'email' => session()->get('user_email')
            ]
        ];

        return view('dashboard', $data);
    }
}