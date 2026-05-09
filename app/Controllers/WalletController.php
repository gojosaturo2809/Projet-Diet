<?php

namespace App\Controllers;

use App\Models\CodeModel;
use App\Models\WalletModel;

class WalletController extends BaseController
{
    public function wallet()
    {
        $userId = $this->getCurrentUserId();

        $walletModel = new WalletModel();

        return view('wallet/wallet', [
            'solde'  => $walletModel->getSoldeUtilisateur($userId),
            'isGold' => (bool) session('is_gold'),
        ]);
    }

    public function recharge()
    {
        return view('wallet/recharge');
    }

    public function addMoney()
    {
        $userId = $this->getCurrentUserId();
        $code = trim((string) $this->request->getPost('code'));

        if ($code === '') {
            return redirect()->back()->with('error', 'Veuillez saisir un code de recharge.');
        }

        $codeModel = new CodeModel();
        $walletModel = new WalletModel();

        $codeData = $this->verifierCode($code, $codeModel);

        if ($codeData === null) {
            return redirect()->back()->with('error', 'Code invalide ou deja utilise.');
        }

        $montant = (float) ($codeData['montant'] ?? 0);

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Le code recharge ne contient aucun montant valide.');
        }

        $walletModel->modifierSolde($userId, $montant);
        $codeModel->desactiverCode((int) $codeData['id'], $userId);

        return redirect()->to(base_url('wallet'))->with('success', 'Rechargement effectue avec succes.');
    }

    public function verifierCode(string $code, ?CodeModel $codeModel = null): ?array
    {
        $model = $codeModel ?? new CodeModel();

        return $model->verifierCode($code);
    }

    private function getCurrentUserId(): int
    {
        return (int) (session('user_id') ?? 1);
    }
}