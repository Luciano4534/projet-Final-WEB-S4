<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\TransactionsModel;
use App\Models\BaremesModel;
use App\Models\TypesOperationsModel;
use App\Models\PrefixesModel;

class ClientController extends BaseController
{
    protected $clientsModel;
    protected $transactionsModel;
    protected $baremesModel;
    protected $typesOpsModel;
    protected $prefixesModel;

    public function __construct()
    {
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->baremesModel      = new BaremesModel();
        $this->typesOpsModel     = new TypesOperationsModel();
        $this->prefixesModel     = new PrefixesModel();
    }

    private function getOperateurForTelephone($telephone)
    {
        $prefix = substr($telephone, 0, 3);
        $prefixe = $this->prefixesModel->where('code', $prefix)->first();
        if ($prefixe) {
            return [
                'operateur'      => $prefixe->operateur,
                'commission_pct' => $prefixe->commission_pct,
            ];
        }
        return null;
    }

    private function getOwnOperateur()
    {
        $ownTelephone = $this->currentUser['telephone'];
        return $this->getOperateurForTelephone($ownTelephone);
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

        return redirect()->to('/client/solde')->with('success', 'Dépôt de ' . number_format($montant, 0, ',', '.') . ' AR effectué avec succès. Frais : ' . number_format($frais, 0, ',', '.') . ' AR.');
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
        $fraisOriginal = $bareme ? $bareme->frais : 0;

        $clientId = $this->currentUser['id'];
        $client = $this->clientsModel->find($clientId);

        $creditDisponible = $client->credit_retrait ?? 0;
        $frais = $fraisOriginal;
        $fraisRetraitUtilise = 0;

        if ($creditDisponible > 0 && $fraisOriginal > 0) {
            if ($creditDisponible >= $fraisOriginal) {
                $fraisRetraitUtilise = $fraisOriginal;
                $frais = 0;
            } else {
                $fraisRetraitUtilise = $creditDisponible;
                $frais = $fraisOriginal - $creditDisponible;
            }
        }

        $total = $montant + $frais;

        if ($client->solde < $total) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : ' . number_format($client->solde, 0, ',', '.') . ' AR. Total requis : ' . number_format($total, 0, ',', '.') . ' AR (montant + frais).');
        }

        $nouveauSolde = $client->solde - $total;
        $nouveauCredit = max(0, $creditDisponible - $fraisRetraitUtilise);

        $this->clientsModel->update($clientId, [
            'solde'          => $nouveauSolde,
            'credit_retrait' => $nouveauCredit,
        ]);

        $this->transactionsModel->save([
            'client_id'         => $clientId,
            'type_operation_id' => $typeRetrait->id,
            'montant'           => $montant,
            'frais'             => $fraisOriginal,
        ]);

        $this->session->set('user.solde', $nouveauSolde);

        $msg = 'Retrait de ' . number_format($montant, 0, ',', '.') . ' AR effectué.';
        if ($fraisRetraitUtilise > 0) {
            $msg .= ' Frais de ' . number_format($fraisOriginal, 0, ',', '.') . ' AR couverts par votre crédit (reste : ' . number_format($nouveauCredit, 0, ',', '.') . ' AR).';
        } else {
            $msg .= ' Frais : ' . number_format($frais, 0, ',', '.') . ' AR.';
        }

        return redirect()->to('/client/solde')->with('success', $msg);
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
        $montant         = $this->request->getPost('montant');
        $telephoneDest   = trim($this->request->getPost('telephone_dest'));
        $inclureRetrait  = $this->request->getPost('inclure_frais_retrait') === '1';

        $rules = [
            'montant'        => 'required|numeric|greater_than[0]',
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
        $fraisFixe = $bareme ? $bareme->frais : 0;

        $ownOp     = $this->getOwnOperateur();
        $destOp    = $this->getOperateurForTelephone($telephoneDest);
        $memeOperateur = ($ownOp && $destOp && $ownOp['operateur'] === $destOp['operateur']);

        $operateurDest = $destOp ? $destOp['operateur'] : '';
        $fraisCommission = 0;
        if (!$memeOperateur && $destOp && $destOp['commission_pct'] > 0) {
            $fraisCommission = $montant * ($destOp['commission_pct'] / 100);
        }

        $fraisRetraitInclus = 0;
        if ($memeOperateur && $inclureRetrait) {
            $typeRetrait = $this->typesOpsModel->where('libelle', 'Retrait')->first();
            if ($typeRetrait) {
                $baremeRetrait = $this->baremesModel->findBareme($typeRetrait->id, $montant);
                $fraisRetraitInclus = $baremeRetrait ? $baremeRetrait->frais : 0;
            }
        }

        $total = $montant + $fraisFixe + $fraisCommission + $fraisRetraitInclus;

        $clientId = $this->currentUser['id'];
        $client = $this->clientsModel->find($clientId);

        if ($client->solde < $total) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : ' . number_format($client->solde, 0, ',', '.') . ' AR. Total requis : ' . number_format($total, 0, ',', '.') . ' AR (montant + frais).');
        }

        $db = \Config\Database::connect();
        $db->transException(true);
        $db->transStart();

        $nouveauSoldeExpediteur = $client->solde - $total;
        $this->clientsModel->update($clientId, ['solde' => $nouveauSoldeExpediteur]);

        $nouveauSoldeDestinataire = $destinataire->solde + $montant;
        $this->clientsModel->update($destinataire->id, ['solde' => $nouveauSoldeDestinataire]);

        if ($fraisRetraitInclus > 0) {
            $nouveauCreditDest = ($destinataire->credit_retrait ?? 0) + $fraisRetraitInclus;
            $this->clientsModel->update($destinataire->id, ['credit_retrait' => $nouveauCreditDest]);
        }

        $this->transactionsModel->save([
            'client_id'              => $clientId,
            'type_operation_id'      => $typeTransfert->id,
            'montant'                => $montant,
            'frais'                  => $fraisFixe,
            'frais_commission'       => $fraisCommission,
            'frais_retrait_inclus'   => $fraisRetraitInclus,
            'telephone_dest'         => $telephoneDest,
            'operateur_dest'         => $operateurDest,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors du transfert. Veuillez réessayer.');
        }

        $this->session->set('user.solde', $nouveauSoldeExpediteur);

        $msg = 'Transfert de ' . number_format($montant, 0, ',', '.') . ' AR vers ' . esc($destinataire->prenom) . ' ' . esc($destinataire->nom) . ' effectué.';
        $details = [];
        if ($fraisFixe > 0) {
            $details[] = 'Frais fixe : ' . number_format($fraisFixe, 0, ',', '.') . ' AR';
        }
        if ($fraisCommission > 0) {
            $details[] = 'Commission ' . $operateurDest . ' : ' . number_format($fraisCommission, 0, ',', '.') . ' AR';
        }
        if ($fraisRetraitInclus > 0) {
            $details[] = 'Frais de retrait prépayés : ' . number_format($fraisRetraitInclus, 0, ',', '.') . ' AR';
        }
        if (!empty($details)) {
            $msg .= ' (' . implode(', ', $details) . ')';
        }

        return redirect()->to('/client/solde')->with('success', $msg);
    }

    public function transfertMultiple()
    {
        $client = $this->clientsModel->find($this->currentUser['id']);
        $ownOp = $this->getOwnOperateur();

        $data = [
            'client'          => $client,
            'ownOperateur'    => $ownOp ? $ownOp['operateur'] : '',
            'title'           => 'Envoi multiple',
        ];
        return $this->render('client/transfert_multiple', $data);
    }

    public function transfertMultiplePost()
    {
        $destinataires = $this->request->getPost('destinataires');

        if (empty($destinataires) || !is_array($destinataires)) {
            return redirect()->back()->withInput()->with('error', 'Aucun destinataire fourni.');
        }

        $clientId = $this->currentUser['id'];
        $client = $this->clientsModel->find($clientId);
        $ownOp = $this->getOwnOperateur();
        $typeTransfert = $this->typesOpsModel->where('libelle', 'Transfert')->first();

        if (!$typeTransfert) {
            return redirect()->back()->withInput()->with('error', 'Type d\'opération "Transfert" non trouvé.');
        }

        $totalDebit = 0;
        $operations = [];
        $operateurRef = null;

        foreach ($destinataires as $i => $dest) {
            $telephone = trim($dest['telephone'] ?? '');
            $montant   = (float) ($dest['montant'] ?? 0);

            if (empty($telephone) || $montant <= 0) {
                return redirect()->back()->withInput()->with('error', 'Destinataire #' . ($i + 1) . ' : numéro ou montant invalide.');
            }

            if ($telephone === $this->currentUser['telephone']) {
                return redirect()->back()->withInput()->with('error', 'Destinataire #' . ($i + 1) . ' : vous ne pouvez pas vous envoyer de l\'argent.');
            }

            $destinataire = $this->clientsModel->findByTelephone($telephone);
            if (!$destinataire) {
                return redirect()->back()->withInput()->with('error', 'Destinataire #' . ($i + 1) . ' : le numéro ' . esc($telephone) . ' n\'existe pas.');
            }

            $destOp = $this->getOperateurForTelephone($telephone);
            if (!$destOp) {
                return redirect()->back()->withInput()->with('error', 'Destinataire #' . ($i + 1) . ' : opérateur non reconnu pour le numéro ' . esc($telephone) . '.');
            }

            if ($operateurRef === null) {
                $operateurRef = $destOp['operateur'];
            } elseif ($destOp['operateur'] !== $operateurRef) {
                return redirect()->back()->withInput()->with('error', 'Envoi multiple limité à un seul opérateur. Le destinataire #' . ($i + 1) . ' appartient à ' . $destOp['operateur'] . ' alors que les autres sont ' . $operateurRef . '.');
            }

            $bareme = $this->baremesModel->findBareme($typeTransfert->id, $montant);
            $fraisFixe = $bareme ? $bareme->frais : 0;

            $memeOperateur = ($ownOp && $ownOp['operateur'] === $destOp['operateur']);
            $fraisCommission = 0;
            if (!$memeOperateur && $destOp['commission_pct'] > 0) {
                $fraisCommission = $montant * ($destOp['commission_pct'] / 100);
            }

            $ligneTotal = $montant + $fraisFixe + $fraisCommission;
            $totalDebit += $ligneTotal;

            $operations[] = [
                'destinataire'     => $destinataire,
                'montant'          => $montant,
                'fraisFixe'        => $fraisFixe,
                'fraisCommission'  => $fraisCommission,
                'operateurDest'    => $destOp['operateur'],
                'memeOperateur'    => $memeOperateur,
            ];
        }

        if ($client->solde < $totalDebit) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde : ' . number_format($client->solde, 0, ',', '.') . ' AR. Total requis : ' . number_format($totalDebit, 0, ',', '.') . ' AR.');
        }

        $db = \Config\Database::connect();
        $db->transException(true);
        $db->transStart();

        $nouveauSolde = $client->solde - $totalDebit;
        $this->clientsModel->update($clientId, ['solde' => $nouveauSolde]);

        foreach ($operations as $op) {
            $dest = $op['destinataire'];
            $this->clientsModel->update($dest->id, ['solde' => $dest->solde + $op['montant']]);

            $this->transactionsModel->save([
                'client_id'            => $clientId,
                'type_operation_id'    => $typeTransfert->id,
                'montant'              => $op['montant'],
                'frais'                => $op['fraisFixe'],
                'frais_commission'     => $op['fraisCommission'],
                'telephone_dest'       => $dest->telephone,
                'operateur_dest'       => $op['operateurDest'],
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'envoi multiple. Veuillez réessayer.');
        }

        $this->session->set('user.solde', $nouveauSolde);

        $nbDest = count($operations);
        return redirect()->to('/client/solde')->with('success', $nbDest . ' transfert(s) effectué(s) pour un total de ' . number_format($totalDebit, 0, ',', '.') . ' AR.');
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
