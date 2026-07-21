<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixesSeed extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            ['code' => '034', 'operateur' => 'Yas',           'commission_pct' => 1,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '038', 'operateur' => 'Yas-New',       'commission_pct' => 1,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '032', 'operateur' => 'Orange-Money',  'commission_pct' => 3,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '037', 'operateur' => 'Orange-Money',  'commission_pct' => 3,    'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('prefixes')->insertBatch($data);
    }
}
