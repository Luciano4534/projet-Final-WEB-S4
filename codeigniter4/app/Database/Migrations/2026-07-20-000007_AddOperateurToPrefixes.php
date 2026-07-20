<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOperateurToPrefixes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('prefixes', [
            'operateur'     => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => '', 'after' => 'code'],
            'commission_pct' => ['type' => 'FLOAT', 'default' => 0, 'after' => 'operateur'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('prefixes', ['operateur', 'commission_pct']);
    }
}
