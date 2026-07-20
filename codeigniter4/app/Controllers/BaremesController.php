<?php

namespace App\Controllers;

use App\Models\BaremesModel;
use App\Models\TypesOperationsModel;

class BaremesController extends BaseController
{
    protected $baremesModel;
    protected $typesOpsModel;

    public function __construct()
    {
        $this->baremesModel  = new BaremesModel();
        $this->typesOpsModel = new TypesOperationsModel();
    }

    public function index()
    {
        $data = [
            'baremes' => $this->baremesModel->getBaremesWithTypes(),
            'title'   => 'Gestion des Barèmes',
        ];
        return $this->render('baremes/list', $data);
    }

    public function create()
    {
        $data = [
            'types' => $this->typesOpsModel->orderBy('libelle', 'ASC')->findAll(),
            'title' => 'Ajouter un Barème',
        ];
        return $this->render('baremes/create', $data);
    }

    public function store()
    {
        $rules = [
            'type_operation_id' => 'required|is_unique[baremes.type_operation_id,montant_min,null]',
            'montant_min'       => 'required|numeric|greater_than_equal_to[0]',
            'montant_max'       => 'required|numeric|greater_than[0]',
            'frais'             => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $this->baremesModel->save([
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max'),
            'frais'             => $this->request->getPost('frais'),
        ]);

        return redirect()->to('/baremes')->with('success', 'Barème ajouté avec succès.');
    }

    public function edit($id)
    {
        $bareme = $this->baremesModel->find($id);

        if (!$bareme) {
            return redirect()->to('/baremes')->with('error', 'Barème non trouvé.');
        }

        $data = [
            'bareme' => $bareme,
            'types'  => $this->typesOpsModel->orderBy('libelle', 'ASC')->findAll(),
            'title'  => 'Modifier le Barème',
        ];
        return $this->render('baremes/edit', $data);
    }

    public function update($id)
    {
        $bareme = $this->baremesModel->find($id);

        if (!$bareme) {
            return redirect()->to('/baremes')->with('error', 'Barème non trouvé.');
        }

        $rules = [
            'type_operation_id' => 'required',
            'montant_min'       => 'required|numeric|greater_than_equal_to[0]',
            'montant_max'       => 'required|numeric|greater_than[0]',
            'frais'             => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $this->baremesModel->update($id, [
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max'),
            'frais'             => $this->request->getPost('frais'),
        ]);

        return redirect()->to('/baremes')->with('success', 'Barème modifié avec succès.');
    }

    public function delete($id)
    {
        $bareme = $this->baremesModel->find($id);

        if (!$bareme) {
            return redirect()->to('/baremes')->with('error', 'Barème non trouvé.');
        }

        $this->baremesModel->delete($id);

        return redirect()->to('/baremes')->with('success', 'Barème supprimé avec succès.');
    }
}
