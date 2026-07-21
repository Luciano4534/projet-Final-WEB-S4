<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = ['cle', 'valeur'];

    public function getValue($cle, $default ='')
    {
       $row = $this -> find($cle);
       return $row ? $row->valeur : $default;
    }

    public function setValue($cle, $valeur)
    {
         $existing = $this->find($cle);
          if ($existing) {
             return $this 
          }
    }
}
