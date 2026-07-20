<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\TransactionsModel;
use App\Models\BaremesModel;
use App\Models\TypesOperationsModel;

class ClientController extends BaseController
{
    protected $clientsModel;
    protected $transactionsModel;
    protected $baremesModel;
    protected $typesOpsModel;

    public function __construct()
    {
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->baremesModel      = new BaremesModel();
        $this->typesOpsModel     = new TypesOperationsModel();
    }

    public function solde()
    {
        $client = $this->clientsModel->find($this->currentUser['id']);
        $this->session->set('user.solde', $client->solde);

        $data = [
            'client' => $client,
            'title'  => 'Mon Solde',
        ];
        return $this->render('client/solde', $data);
    }

    public function depot()
    {
        $data = [
            'title' => 'Dépôt d\'argent',
        ];
        return $this->render('client/depot', $data);
    }

    public function depotPost()
    {
        $montant = $this->request->getPost('montant');

        $rules = [
            'montant' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $montant = (float) $montant;
        $typeDepot = $this->typesOpsModel->where('libelle', 'Dépôt')->first();

        if (!$typeDepot) {
            return redirect()->back()->withInput()->with('error', 'Type d\'opération "Dépôt" non trouvé.');
        }

        $bareme = $this->baremesModel->findBareme($typeDepot->id, $montant);
        $frais = $bareme ? $bareme->frais : 0;

        $clientId = $this->currentUser['id'];
        $client = $this->clientsModel->find($clientId);
        $nouveauSolde = $client->solde + $montant;

        $this->clientsModel->update($clientId, ['solde' => $nouveauSolde]);

        $this->transactionsModel->save([
            'client_id'         => $clientId,
            'type_operation_id' => $typeDepot->id,
            'montant'           => $montant,
            'frais'             => $frais,
        ]);

        $this->session->set('user.solde', $nouveauSolde);

        return redirect()->to('/client/solde')->with('success', 'Dépôt de ' . number_format($montant, 0, ',', '.') . ' F effectué avec succès. Frais : ' . number_format($frais, 0, ',', '.') . ' F.');
    }

    public function retrait()
    {
        $client = $this->clientsModel->find($this->currentUser['id']);
        $data = [
            'client' => $client,
            'title'  => 'Retrait d\'argent',
        ];
        return $this->render('client/retrait', $data);
    }

    public function retraitPost()
    {
        $montant = $this->request->getPost('montant');

        $rules = [
            'montant' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $montant = (float) $montant;
        $typeRetrait = $this->typesOpsModel->where('libelle', 'Retrait')->first();

        if (!$typeRetrait) {
            return redirect()->back()->withInput()->with('error', 'Type d\'opération "Retrait" non trouvé.');
        }

        $bareme = $this->baremesModel->findBareme($typeRetrait->id, $montant);
        $frais = $bareme ? $bareme->frais : 0;

        $clientId = $this->currentUser['id'];
        $client = $this->clientsModel->find($clientId);
        $total = $montant + $frais;

        if ($client->solde < $total) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : ' . number_format($client->solde, 0, ',', '.') . ' F. Total requis : ' . number_format($total, 0, ',', '.') . ' F (montant + frais).');
        }

        $nouveauSolde = $client->solde - $total;
        $this->clientsModel->update($clientId, ['solde' => $nouveauSolde]);

        $this->transactionsModel->save([
            'client_id'         => $clientId,
            'type_operation_id' => $typeRetrait->id,
            'montant'           => $montant,
            'frais'             => $frais,
        ]);

        $this->session->set('user.solde', $nouveauSolde);

        return redirect()->to('/client/solde')->with('success', 'Retrait de ' . number_format($montant, 0, ',', '.') . ' F effectué. Frais : ' . number_format($frais, 0, ',', '.') . ' F.');
    }

    public function transfert()
    {
        $client = $this->clientsModel->find($this->currentUser['id']);
        $data = [
            'client' => $client,
            'title'  => 'Transfert d\'argent',
        ];
        return $this->render('client/transfert', $data);
    }

    public function transfertPost()
    {
        $montant      = $this->request->getPost('montant');
        $telephoneDest = trim($this->request->getPost('telephone_dest'));

        $rules = [
            'montant'       => 'required|numeric|greater_than[0]',
            'telephone_dest' => 'required|min_length[10]|max_length[15]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $montant = (float) $montant;

        if ($telephoneDest === $this->currentUser['telephone']) {
            return redirect()->back()->withInput()->with('error', 'Vous ne pouvez pas transférer de l\'argent à vous-même.');
        }

        $destinataire = $this->clientsModel->findByTelephone($telephoneDest);

        if (!$destinataire) {
            return redirect()->back()->withInput()->with('error', 'Le numéro ' . esc($telephoneDest) . ' ne correspond à aucun client.');
        }

        $typeTransfert = $this->typesOpsModel->where('libelle', 'Transfert')->first();

        if (!$typeTransfert) {
            return redirect()->back()->withInput()->with('error', 'Type d\'opération "Transfert" non trouvé.');
        }

        $bareme = $this->baremesModel->findBareme($typeTransfert->id, $montant);
        $frais = $bareme ? $bareme->frais : 0;

        $clientId = $this->currentUser['id'];
        $client = $this->clientsModel->find($clientId);
        $total = $montant + $frais;

        if ($client->solde < $total) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : ' . number_format($client->solde, 0, ',', '.') . ' F. Total requis : ' . number_format($total, 0, ',', '.') . ' F (montant + frais).');
        }

        $db = \Config\Database::connect();
        $db->transException(true);
        $db->transStart();

        $nouveauSoldeExpediteur = $client->solde - $total;
        $this->clientsModel->update($clientId, ['solde' => $nouveauSoldeExpediteur]);

        $nouveauSoldeDestinataire = $destinataire->solde + $montant;
        $this->clientsModel->update($destinataire->id, ['solde' => $nouveauSoldeDestinataire]);

        $this->transactionsModel->save([
            'client_id'         => $clientId,
            'type_operation_id' => $typeTransfert->id,
            'montant'           => $montant,
            'frais'             => $frais,
            'telephone_dest'    => $telephoneDest,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors du transfert. Veuillez réessayer.');
        }

        $this->session->set('user.solde', $nouveauSoldeExpediteur);

        return redirect()->to('/client/solde')->with('success', 'Transfert de ' . number_format($montant, 0, ',', '.') . ' F vers ' . esc($destinataire->prenom) . ' ' . esc($destinataire->nom) . ' effectué. Frais : ' . number_format($frais, 0, ',', '.') . ' F.');
    }

    public function historique()
    {
        $clientId = $this->currentUser['id'];
        $pager = \Config\Services::pager();

        $transactions = $this->transactionsModel
            ->select('transactions.*, types_operations.libelle as type_libelle')
            ->join('types_operations', 'types_operations.id = transactions.type_operation_id')
            ->where('transactions.client_id', $clientId)
            ->orderBy('transactions.created_at', 'DESC')
            ->paginate(10);

        $data = [
            'transactions' => $transactions,
            'pager'        => $pager,
            'title'        => 'Historique des opérations',
        ];
        return $this->render('client/historique', $data);
    }
}
