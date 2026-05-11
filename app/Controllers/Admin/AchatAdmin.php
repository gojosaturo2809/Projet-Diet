<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AchatModel;

class AchatAdmin extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $model = new AchatModel();
        $achats = $model->getAchatsEnAttente();

        return view('admin/achats/index', ['achats' => $achats]);
    }

    public function confirm($id)
    {
        $model = new AchatModel();
        $success = $model->confirmAchat((int) $id);

        if ($success) {
            return redirect()->to('/admin/achats')->with('success', 'Achat confirmé et portefeuille débité.');
        } else {
            return redirect()->back()->with('error', 'Impossible de confirmer cet achat.');
        }
    }

    public function reject($id)
    {
        $motif = $this->request->getPost('motif') ?? '';
        $model = new AchatModel();
        $success = $model->rejectAchat((int) $id, $motif);

        if ($success) {
            return redirect()->to('/admin/achats')->with('success', 'Achat rejeté et remboursement effectué.');
        } else {
            return redirect()->back()->with('error', 'Impossible de rejeter cet achat.');
        }
    }
}
