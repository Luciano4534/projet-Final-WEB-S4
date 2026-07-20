<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremesModel extends Model
{
    protected $table            = 'baremes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];

    public function getTypeOperation()
    {
        return $this->belongsTo('App\Models\TypesOperationsModel', 'type_operation_id');
    }

    public function getBaremesWithTypes()
    {
        return $this->select('baremes.*, types_operations.libelle as type_libelle')
            ->join('types_operations', 'types_operations.id = baremes.type_operation_id')
            ->orderBy('baremes.type_operation_id', 'ASC')
            ->orderBy('baremes.montant_min', 'ASC')
            ->findAll();
    }

    public function findBareme($typeOperationId, $montant)
    {
        return $this->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();
    }
}
