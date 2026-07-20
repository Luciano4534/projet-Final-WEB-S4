<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeed extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('clients')->insert([
            'nom'        => 'Admin',
            'prenom'     => 'Super',
            'telephone'  => '0330000000',
            'solde'      => 0,
            'role'       => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
