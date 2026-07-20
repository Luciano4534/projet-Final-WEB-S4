<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixesModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = ['code', 'operateur', 'commission_pct'];

    public function getOperateurs()
    {
        return $this->select('operateur, commission_pct')
            ->where('operateur !=', '')
            ->groupBy('operateur')
            ->orderBy('operateur', 'ASC')
            ->findAll();
    }

    public function getPrefixesByOperateur()
    {
        return $this->orderBy('operateur', 'ASC')
            ->orderBy('code', 'ASC')
            ->findAll();
    }
}
