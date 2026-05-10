<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegimeModel;

class RegimeAdmin extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $model = new RegimeModel();

        $data['regimes'] = $model->getAllWithComposition();

        return view('admin/regimes/index', $data);
    }

    public function create()
    {
        return view('admin/regimes/create');
    }

    public function store()
    {
        $model = new RegimeModel();

        $regimeData = [

            'nom' => $this->request->getPost('nom'),

            'description' => $this->request->getPost('description'),

            'prix_journalier' => $this->request->getPost('prix_journalier'),

            'variation_poids_hebdo' => $this->request->getPost('variation_poids_hebdo'),

            'poids_min_requis' => $this->request->getPost('poids_min_requis'),
        ];

        $model->insert($regimeData);

        $regimeId = $model->insertID();

        $this->db->table('regime_composition')->insert([

            'id_regime' => $regimeId,

            'pourcentage_viande' =>
                $this->request->getPost('pourcentage_viande'),

            'pourcentage_poisson' =>
                $this->request->getPost('pourcentage_poisson'),

            'pourcentage_volaille' =>
                $this->request->getPost('pourcentage_volaille'),
        ]);

        return redirect()
            ->to('/admin/regimes')
            ->with('success', 'Régime ajouté');
    }

    public function delete($id)
    {
        $model = new RegimeModel();

        $model->delete($id);

        return redirect()
            ->back()
            ->with('success', 'Régime supprimé');
    }
}