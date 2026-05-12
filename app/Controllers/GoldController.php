<?php

namespace App\Controllers;

use App\Models\WalletModel;

class GoldController extends BaseController
{
    public function index()
    {
        $userId = (int) (session('user_id') ?? 0);
        $walletModel = new WalletModel();

        return view('gold/gold', [
            'isGold' => (bool) session('is_gold'),
            'solde' => $userId > 0 ? $walletModel->getSoldeUtilisateur($userId) : null,
        ]);
    }

    public function activateGold()
    {
        $userId = (int) (session('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to(base_url('login'))->with('error', 'Veuillez vous connecter.');
        }

        $walletModel = new WalletModel();
        $success = $walletModel->activerGold($userId, WalletModel::GOLD_PRICE);

        if (! $success) {
            return redirect()->to(base_url('gold'))->with('error', 'Solde insuffisant pour activer Gold.');
        }

        session()->set('is_gold', true);

        return redirect()->to(base_url('gold'))->with('success', 'Option Gold activee avec succes.');
    }
}