<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypesOperationsSeed extends Seeder
{
    public function run()
    {
        $data = [
            ['libelle' => 'Dépôt', 'description' => 'Dépôt d\'argent sur un compte', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['libelle' => 'Retrait', 'description' => 'Retrait d\'argent d\'un compte', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['libelle' => 'Transfert', 'description' => 'Transfert d\'argent vers un autre compte', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('types_operations')->insertBatch($data);
    }
}
