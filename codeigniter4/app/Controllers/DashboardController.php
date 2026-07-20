<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\TransactionsModel;

class DashboardController extends BaseController
{
    protected $clientsModel;
    protected $transactionsModel;

    public function __construct()
    {
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
    }

    public function index()
    {
        $data = [
            'nbClients'        => $this->clientsModel->countAllResults(),
            'nbTransactions'   => $this->transactionsModel->getTotalTransactions(),
            'totalMontant'     => $this->transactionsModel->getTotalMontant(),
            'totalFrais'       => $this->transactionsModel->getTotalFrais(),
            'fraisByType'      => $this->transactionsModel->getFraisByType(),
            'title'            => 'Tableau de bord',
        ];
        return $this->render('dashboard/index', $data);
    }

    public function clients()
    {
        $search = $this->request->getGet('search');
        $builder = $this->clientsModel->builder();
        $builder->orderBy('nom', 'ASC');

        if ($search) {
            $builder->groupStart()
                ->like('nom', $search)
                ->orLike('prenom', $search)
                ->orLike('telephone', $search)
                ->groupEnd();
        }

        $clients = $builder->get()->getResult();

        $data = [
            'clients' => $clients,
            'search'  => $search,
            'title'   => 'Situation des comptes clients',
        ];
        return $this->render('dashboard/clients', $data);
    }

    public function gains()
    {
        $fraisInternes = $this->transactionsModel->getFraisInternes();
        $fraisExternes = $this->transactionsModel->getFraisExternes();
        $montantsParOperateur = $this->transactionsModel->getMontantsParOperateur();

        $totalFraisInternes = 0;
        foreach ($fraisInternes as $fi) {
            $totalFraisInternes += $fi->total_frais;
        }

        $totalFraisExternes = 0;
        foreach ($fraisExternes as $fe) {
            $totalFraisExternes += $fe->total_frais;
        }

        $totalFraisCommission = 0;
        foreach ($fraisExternes as $fe) {
            $totalFraisCommission += $fe->total_frais_commission;
        }

        $data = [
            'fraisInternes'          => $fraisInternes,
            'totalFraisInternes'     => $totalFraisInternes,
            'fraisExternes'          => $fraisExternes,
            'totalFraisExternes'     => $totalFraisExternes,
            'totalFraisCommission'   => $totalFraisCommission,
            'montantsParOperateur'   => $montantsParOperateur,
            'totalFrais'             => $this->transactionsModel->getTotalFrais() + $this->transactionsModel->getTotalFraisCommission(),
            'title'                  => 'Gains de l\'opérateur',
        ];
        return $this->render('dashboard/gains', $data);
    }
}
