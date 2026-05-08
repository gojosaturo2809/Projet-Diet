<?php

namespace App\Controllers;

use App\Models\InscrptionModele;

class Inscription extends BaseController{
    
    public function inscription(){
        try {
            $modele = new InscrptionModele();
            $data = $this->request->getPost();

            if(!$modele->insert($data)){
                $errors = $modele->errors();
                return view('inscription/inscription', ['errors' => $errors]);
            }
            else 
                return "Validation réussie !";
        } catch (\Exception $e) {
            // Affiche l'erreur réelle
            return "Erreur : " . $e->getMessage();
        }
    }
    public function index(){
        return view('inscription/inscription');
    }
}
