<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixesSeed extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            ['code' => '033', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '034', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '035', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '036', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '031', 'operateur' => 'Airtel',      'commission_pct' => 2.5,  'created_at' => $now, 'updated_at' => $now],
            ['code' => '032', 'operateur' => 'Orange',      'commission_pct' => 3.0,  'created_at' => $now, 'updated_at' => $now],
            ['code' => '037', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '038', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '039', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '099', 'operateur' => 'MPesa',       'commission_pct' => 2.0,  'created_at' => $now, 'updated_at' => $now],
            ['code' => '050', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '051', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
            ['code' => '052', 'operateur' => 'Vodacom',     'commission_pct' => 0,    'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('prefixes')->insertBatch($data);
    }
}
