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
        return view('admin/regimes/create', [
            'regime' => [],
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function store()
    {
        $model = new RegimeModel();

        $data = $this->request->getPost([
            'nom',
            'description',
            'prix_journalier',
            'variation_poids_hebdo',
            'poids_min_requis',
            'pourcentage_viande',
            'pourcentage_poisson',
            'pourcentage_volaille',
        ]);

        if (! $this->validate([
            'nom' => 'required|min_length[2]|max_length[255]',
            'description' => 'required|min_length[3]',
            'prix_journalier' => 'required|numeric|greater_than_equal_to[0]',
            'variation_poids_hebdo' => 'required|numeric',
            'poids_min_requis' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'pourcentage_viande' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_poisson' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_volaille' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $regimeData = [
            'nom' => trim((string) ($data['nom'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'prix_journalier' => (float) ($data['prix_journalier'] ?? 0),
            'variation_poids_hebdo' => (float) ($data['variation_poids_hebdo'] ?? 0),
            'poids_min_requis' => (float) ($data['poids_min_requis'] ?? 0),
        ];

        $this->db->transStart();

        $model->insert($regimeData);
        $regimeId = (int) $model->insertID();

        $this->db->table('regime_composition')->insert([
            'id_regime' => $regimeId,
            'pourcentage_viande' => (int) ($data['pourcentage_viande'] ?? 0),
            'pourcentage_poisson' => (int) ($data['pourcentage_poisson'] ?? 0),
            'pourcentage_volaille' => (int) ($data['pourcentage_volaille'] ?? 0),
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Impossible de créer le régime.');
        }

        return redirect()
            ->to('/admin/regimes')
            ->with('success', 'Régime ajouté');
    }

    public function edit($id)
    {
        $model = new RegimeModel();
        $regime = $model->getWithComposition((int) $id);

        if (! $regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime introuvable.');
        }

        return view('admin/regimes/edit', [
            'regime' => $regime,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update($id)
    {
        $model = new RegimeModel();
        $regime = $model->find((int) $id);

        if (! $regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime introuvable.');
        }

        $data = $this->request->getPost([
            'nom',
            'description',
            'prix_journalier',
            'variation_poids_hebdo',
            'poids_min_requis',
            'pourcentage_viande',
            'pourcentage_poisson',
            'pourcentage_volaille',
        ]);

        if (! $this->validate([
            'nom' => 'required|min_length[2]|max_length[255]',
            'description' => 'required|min_length[3]',
            'prix_journalier' => 'required|numeric|greater_than_equal_to[0]',
            'variation_poids_hebdo' => 'required|numeric',
            'poids_min_requis' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'pourcentage_viande' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_poisson' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_volaille' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->transStart();

        $model->update((int) $id, [
            'nom' => trim((string) ($data['nom'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'prix_journalier' => (float) ($data['prix_journalier'] ?? 0),
            'variation_poids_hebdo' => (float) ($data['variation_poids_hebdo'] ?? 0),
            'poids_min_requis' => (float) ($data['poids_min_requis'] ?? 0),
        ]);

        $composition = [
            'pourcentage_viande' => (int) ($data['pourcentage_viande'] ?? 0),
            'pourcentage_poisson' => (int) ($data['pourcentage_poisson'] ?? 0),
            'pourcentage_volaille' => (int) ($data['pourcentage_volaille'] ?? 0),
        ];

        $exists = $this->db->table('regime_composition')
            ->where('id_regime', (int) $id)
            ->countAllResults();

        if ($exists > 0) {
            $this->db->table('regime_composition')
                ->where('id_regime', (int) $id)
                ->update($composition);
        } else {
            $this->db->table('regime_composition')->insert(array_merge(['id_regime' => (int) $id], $composition));
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Impossible de mettre à jour le régime.');
        }

        return redirect()->to('/admin/regimes')->with('success', 'Régime modifié');
    }

    public function delete($id)
    {
        $model = new RegimeModel();

        $this->db->table('regime_composition')->where('id_regime', (int) $id)->delete();
        $model->delete((int) $id);

        return redirect()
            ->to('/admin/regimes')
            ->with('success', 'Régime supprimé');
    }
}