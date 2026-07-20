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

    protected $allowedFields = ['client_id', 'type_operation_id', 'montant', 'frais', 'telephone_dest'];

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
}
