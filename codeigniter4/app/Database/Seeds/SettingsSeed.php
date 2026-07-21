<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeed extends Seeder
{
    public function run()
    {
        $this->db->table('Settings')->insert([
           
         'cle'     => 'promotion transfert_meme_operateur',
         'valeur'  => '10',
        ]);
    }
}
