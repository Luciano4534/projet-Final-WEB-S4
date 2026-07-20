<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientsModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = ['nom', 'prenom', 'telephone', 'solde', 'role', 'credit_retrait'];

    public function findByTelephone($telephone)
    {
        return $this->where('telephone', $telephone)->first();
    }

    public function getSolde($clientId)
    {
        $client = $this->find($clientId);
        return $client ? $client->solde : 0;
    }
}
