<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'client_id', 'type_operation_id', 'montant', 'frais',
        'frais_commission', 'frais_retrait_inclus',
        'telephone_dest', 'operateur_dest',
    ];

    public function getTransactionsWithDetails()
    {
        return $this->select('transactions.*, clients.nom, clients.prenom, clients.telephone, types_operations.libelle as type_libelle')
            ->join('clients', 'clients.id = transactions.client_id')
            ->join('types_operations', 'types_operations.id = transactions.type_operation_id')
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();
    }

    public function getTransactionsByClient($clientId)
    {
        return $this->select('transactions.*, types_operations.libelle as type_libelle')
            ->join('types_operations', 'types_operations.id = transactions.type_operation_id')
            ->where('transactions.client_id', $clientId)
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();
    }

    public function getTotalTransactions()
    {
        return $this->countAllResults();
    }

    public function getTotalMontant()
    {
        $builder = $this->db->table('transactions');
        $result = $builder->selectSum('montant')->get()->getRow();
        return $result->montant ?? 0;
    }

    public function getTotalFrais()
    {
        $builder = $this->db->table('transactions');
        $result = $builder->selectSum('frais')->get()->getRow();
        return $result->frais ?? 0;
    }

    public function getFraisByType()
    {
        $builder = $this->db->table('transactions');
        return $builder->select('types_operations.libelle as type_libelle, SUM(transactions.frais) as total_frais, COUNT(transactions.id) as nb_transactions')
            ->join('types_operations', 'types_operations.id = transactions.type_operation_id')
            ->groupBy('transactions.type_operation_id')
            ->get()
            ->getResult();
    }

    public function getTotalFraisCommission()
    {
        $builder = $this->db->table('transactions');
        $result = $builder->selectSum('frais_commission')->get()->getRow();
        return $result->frais_commission ?? 0;
    }

    public function getTotalFraisRetraitInclus()
    {
        $builder = $this->db->table('transactions');
        $result = $builder->selectSum('frais_retrait_inclus')->get()->getRow();
        return $result->frais_retrait_inclus ?? 0;
    }

    public function getFraisInternes()
    {
        $builder = $this->db->table('transactions');
        return $builder->select('types_operations.libelle as type_libelle, SUM(transactions.frais) as total_frais, COUNT(transactions.id) as nb_transactions')
            ->join('types_operations', 'types_operations.id = transactions.type_operation_id')
            ->groupStart()
                ->where('transactions.type_operation_id !=', 3)
                ->orWhere('transactions.operateur_dest', '')
            ->groupEnd()
            ->groupBy('transactions.type_operation_id')
            ->get()
            ->getResult();
    }

    public function getFraisExternes()
    {
        $builder = $this->db->table('transactions');
        return $builder->select('operateur_dest, SUM(transactions.frais) as total_frais_fixe, SUM(transactions.frais_commission) as total_frais_commission, SUM(transactions.frais + transactions.frais_commission) as total_frais, COUNT(transactions.id) as nb_transactions')
            ->where('transactions.type_operation_id', 3)
            ->where('transactions.operateur_dest !=', '')
            ->groupBy('transactions.operateur_dest')
            ->get()
            ->getResult();
    }

    public function getMontantsParOperateur()
    {
        $builder = $this->db->table('transactions');
        return $builder->select('operateur_dest, SUM(transactions.montant) as total_montant, SUM(transactions.frais) as total_frais_fixe, SUM(transactions.frais_commission) as total_commission, SUM(transactions.montant + transactions.frais + transactions.frais_commission) as total_a_verser, COUNT(transactions.id) as nb_transactions')
            ->where('transactions.type_operation_id', 3)
            ->where('transactions.operateur_dest !=', '')
            ->groupBy('transactions.operateur_dest')
            ->get()
            ->getResult();
    }

    public function getCreditRetraitForClient($clientId)
    {
        $builder = $this->db->table('transactions');
        $result = $builder->selectSum('frais_retrait_inclus')
            ->where('telephone_dest', '')
            ->where('frais_retrait_inclus >', 0)
            ->get()
            ->getRow();
        return 0;
    }
}
