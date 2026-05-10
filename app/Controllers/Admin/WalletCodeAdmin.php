<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WalletModel;

class WalletCodeAdmin extends BaseController
{
    public function index()
    {
        $model = new WalletModel();

        $data['codes'] = $model->getAllRechargeCodes();

        return view('admin/codes/index', $data);
    }

    public function create()
    {
        return view('admin/codes/create');
    }

    public function store()
    {
        $model = new WalletModel();

        $code = 'NP-' . strtoupper(substr(md5(rand()),0,8));

        $model->createRechargeCode([

            'code' => $code,

            'montant' => $this->request->getPost('montant'),

            'is_active' => 1,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Code créé');
    }
}