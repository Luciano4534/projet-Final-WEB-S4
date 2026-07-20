<?php

namespace App\Controllers;

use App\Models\TypesOperationsModel;

class TypesOperationsController extends BaseController
{
    protected $typesOpsModel;

    public function __construct()
    {
        $this->typesOpsModel = new TypesOperationsModel();
    }

    public function index()
    {
        $data = [
            'types'  => $this->typesOpsModel->orderBy('id', 'ASC')->findAll(),
            'title'  => 'Gestion des Types d\'Opérations',
        ];
        return $this->render('types_operations/list', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Ajouter un Type d\'Opération',
        ];
        return $this->render('types_operations/create', $data);
    }

    public function store()
    {
        $rules = [
            'libelle' => 'required|max_length[100]|is_unique[types_operations.libelle]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $this->typesOpsModel->save([
            'libelle'     => $this->request->getPost('libelle'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/types-operations')->with('success', 'Type d\'opération ajouté avec succès.');
    }

    public function edit($id)
    {
        $typeOp = $this->typesOpsModel->find($id);

        if (!$typeOp) {
            return redirect()->to('/types-operations')->with('error', 'Type d\'opération non trouvé.');
        }

        $data = [
            'typeOp' => $typeOp,
            'title'  => 'Modifier le Type d\'Opération',
        ];
        return $this->render('types_operations/edit', $data);
    }

    public function update($id)
    {
        $typeOp = $this->typesOpsModel->find($id);

        if (!$typeOp) {
            return redirect()->to('/types-operations')->with('error', 'Type d\'opération non trouvé.');
        }

        $rules = [
            'libelle' => "required|max_length[100]|is_unique[types_operations.libelle,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $this->typesOpsModel->update($id, [
            'libelle'     => $this->request->getPost('libelle'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/types-operations')->with('success', 'Type d\'opération modifié avec succès.');
    }

    public function delete($id)
    {
        $typeOp = $this->typesOpsModel->find($id);

        if (!$typeOp) {
            return redirect()->to('/types-operations')->with('error', 'Type d\'opération non trouvé.');
        }

        $this->typesOpsModel->delete($id);

        return redirect()->to('/types-operations')->with('success', 'Type d\'opération supprimé avec succès.');
    }
}
