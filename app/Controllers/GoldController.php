<?php

namespace App\Controllers;

use App\Models\WalletModel;

class GoldController extends BaseController
{
    public function index()
    {
        return view('gold/gold', [
            'isGold' => (bool) session('is_gold'),
        ]);
    }

    public function activateGold()
    {
        $userId = (int) (session('user_id') ?? 1);

        $walletModel = new WalletModel();
        $walletModel->activerGold($userId);

        session()->set('is_gold', true);

        return redirect()->to(base_url('gold'))->with('success', 'Option Gold activee avec succes.');
    }
}