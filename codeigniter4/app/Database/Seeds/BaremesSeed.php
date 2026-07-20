<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BaremesSeed extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            // Dépôt (type_operation_id = 1)
            ['type_operation_id' => 1, 'montant_min' => 0, 'montant_max' => 10000, 'frais' => 100, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 1, 'montant_min' => 10001, 'montant_max' => 50000, 'frais' => 250, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 1, 'montant_min' => 50001, 'montant_max' => 100000, 'frais' => 500, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 1, 'montant_min' => 100001, 'montant_max' => 500000, 'frais' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 1, 'montant_min' => 500001, 'montant_max' => 1000000, 'frais' => 2000, 'created_at' => $now, 'updated_at' => $now],

            // Retrait (type_operation_id = 2)
            ['type_operation_id' => 2, 'montant_min' => 0, 'montant_max' => 10000, 'frais' => 150, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 2, 'montant_min' => 10001, 'montant_max' => 50000, 'frais' => 350, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 2, 'montant_min' => 50001, 'montant_max' => 100000, 'frais' => 700, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 2, 'montant_min' => 100001, 'montant_max' => 500000, 'frais' => 1500, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 2, 'montant_min' => 500001, 'montant_max' => 1000000, 'frais' => 3000, 'created_at' => $now, 'updated_at' => $now],

            // Transfert (type_operation_id = 3)
            ['type_operation_id' => 3, 'montant_min' => 0, 'montant_max' => 10000, 'frais' => 200, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 3, 'montant_min' => 10001, 'montant_max' => 50000, 'frais' => 500, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 3, 'montant_min' => 50001, 'montant_max' => 100000, 'frais' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 3, 'montant_min' => 100001, 'montant_max' => 500000, 'frais' => 2000, 'created_at' => $now, 'updated_at' => $now],
            ['type_operation_id' => 3, 'montant_min' => 500001, 'montant_max' => 1000000, 'frais' => 4000, 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('baremes')->insertBatch($data);
    }
}
