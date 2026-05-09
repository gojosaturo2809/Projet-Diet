<?php

namespace App\Controllers;

class Login extends BaseController{

public function login(){
    try {
        $model = new \App\Models\LoginModel();
        $data = $this->request->getPost();

        if(!$model->validate($data)){
            $errors = $model->errors();
            return view('login/login', ['errors' => $errors]);
        }
        else 
            return "Validation réussie !";
    } catch (\Exception $e) {
        // Affiche l'erreur réelle
        return "Erreur : " . $e->getMessage();
    }
}
}