<?php

namespace App\Controllers;

use App\Models\PrefixesModel;

class PrefixesController extends BaseController
{
    protected $prefixesModel;

    public function __construct()
    {
        $this->prefixesModel = new PrefixesModel();
    }

    public function index()
    {
        $data = [
            'prefixes' => $this->prefixesModel->orderBy('code', 'ASC')->findAll(),
            'title'    => 'Gestion des Préfixes',
        ];
        return $this->render('prefixes/list', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Ajouter un Préfixe',
        ];
        return $this->render('prefixes/create', $data);
    }

    public function store()
    {
        $rules = [
            'code' => 'required|min_length[3]|max_length[10]|is_unique[prefixes.code]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $this->prefixesModel->save([
            'code' => $this->request->getPost('code'),
        ]);

        return redirect()->to('/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    public function edit($id)
    {
        $prefixe = $this->prefixesModel->find($id);

        if (!$prefixe) {
            return redirect()->to('/prefixes')->with('error', 'Préfixe non trouvé.');
        }

        $data = [
            'prefixe' => $prefixe,
            'title'   => 'Modifier le Préfixe',
        ];
        return $this->render('prefixes/edit', $data);
    }

    public function update($id)
    {
        $prefixe = $this->prefixesModel->find($id);

        if (!$prefixe) {
            return redirect()->to('/prefixes')->with('error', 'Préfixe non trouvé.');
        }

        $rules = [
            'code' => "required|min_length[3]|max_length[10]|is_unique[prefixes.code,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $this->prefixesModel->update($id, [
            'code' => $this->request->getPost('code'),
        ]);

        return redirect()->to('/prefixes')->with('success', 'Préfixe modifié avec succès.');
    }

    public function delete($id)
    {
        $prefixe = $this->prefixesModel->find($id);

        if (!$prefixe) {
            return redirect()->to('/prefixes')->with('error', 'Préfixe non trouvé.');
        }

        $this->prefixesModel->delete($id);

        return redirect()->to('/prefixes')->with('success', 'Préfixe supprimé avec succès.');
    }
}
