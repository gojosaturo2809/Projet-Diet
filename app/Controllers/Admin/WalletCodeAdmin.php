<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CodeModel;

class WalletCodeAdmin extends BaseController
{
    public function index()
    {
        $model = new CodeModel();

        $data['codes'] = $model->getAllWithUsers();

        return view('admin/codes/index', $data);
    }

    public function create()
    {
        return view('admin/codes/create', [
            'code' => [],
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function store()
    {
        $model = new CodeModel();

        if (! $this->validate([
            'montant' => 'required|numeric|greater_than[0]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $code = 'NP-' . strtoupper(bin2hex(random_bytes(4)));

        $model->createCode([

            'code' => $code,

            'montant' => (float) $this->request->getPost('montant'),

            'is_active' => 1,
        ]);

        return redirect()
            ->to('/admin/codes')
            ->with('success', 'Code créé');
    }

    public function edit($id)
    {
        $model = new CodeModel();
        $code = $model->getOne((int) $id);

        if (! $code) {
            return redirect()->to('/admin/codes')->with('error', 'Code introuvable.');
        }

        return view('admin/codes/edit', [
            'code' => $code,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update($id)
    {
        $model = new CodeModel();
        $code = $model->getOne((int) $id);

        if (! $code) {
            return redirect()->to('/admin/codes')->with('error', 'Code introuvable.');
        }

        if (! $this->validate([
            'montant' => 'required|numeric|greater_than[0]',
            'is_active' => 'required|in_list[0,1]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update((int) $id, [
            'montant' => (float) $this->request->getPost('montant'),
            'is_active' => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code modifié');
    }

    public function delete($id)
    {
        $model = new CodeModel();
        $model->delete((int) $id);

        return redirect()->to('/admin/codes')->with('success', 'Code supprimé');
    }
}