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
            'prefixes' => $this->prefixesModel->orderBy('operateur', 'ASC')->orderBy('code', 'ASC')->findAll(),
            'title'    => 'Gestion des Préfixes',
        ];
        return $this->render('prefixes/list', $data);
    }

    public function create()
    {
        $data = [
            'operateurs' => $this->prefixesModel->getOperateurs(),
            'title'      => 'Ajouter un Préfixe',
        ];
        return $this->render('prefixes/create', $data);
    }

    public function store()
    {
        $rules = [
            'code'          => 'required|min_length[3]|max_length[10]|is_unique[prefixes.code]',
            'operateur'     => 'required|max_length[50]',
            'commission_pct' => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $this->prefixesModel->save([
            'code'           => $this->request->getPost('code'),
            'operateur'      => trim($this->request->getPost('operateur')),
            'commission_pct' => (float) ($this->request->getPost('commission_pct') ?? 0),
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
            'prefixe'    => $prefixe,
            'operateurs' => $this->prefixesModel->getOperateurs(),
            'title'      => 'Modifier le Préfixe',
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
            'code'          => "required|min_length[3]|max_length[10]|is_unique[prefixes.code,id,{$id}]",
            'operateur'     => 'required|max_length[50]',
            'commission_pct' => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $newOperateur = trim($this->request->getPost('operateur'));
        $newPct       = (float) ($this->request->getPost('commission_pct') ?? 0);

        $this->prefixesModel->update($id, [
            'code'           => $this->request->getPost('code'),
            'operateur'      => $newOperateur,
            'commission_pct' => $newPct,
        ]);

        $existingPct = $this->prefixesModel->where('operateur', $newOperateur)->where('id !=', $id)->findAll();
        foreach ($existingPct as $p) {
            $this->prefixesModel->update($p->id, ['commission_pct' => $newPct]);
        }

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
