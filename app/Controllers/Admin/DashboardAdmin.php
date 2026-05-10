<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\WalletModel;
use App\Models\LoginModel;

class DashboardAdmin extends BaseController
{
    public function index()
    {
        $regimeModel = new RegimeModel();
        $walletModel = new WalletModel();
        $loginModel  = new LoginModel();

        $data = [

            // statistiques
            'totalUsers' => $loginModel->countUsers(),

            'totalRegimes' => $regimeModel->countRegimes(),

            'totalCodes' => $walletModel->countCodes(),

            'revenuTotal' => $regimeModel->getTotalRevenue(),

            // tableaux
            'regimes' => $regimeModel->getAllWithComposition(),

            'codes' => $walletModel->getAllRechargeCodes(),
        ];

        return view('admin/dashboard', $data);
    }
    
}