<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnrichTransactionsAndClients extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'operateur_dest'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => '', 'after' => 'telephone_dest'],
            'frais_commission'     => ['type' => 'FLOAT', 'default' => 0, 'after' => 'frais'],
            'frais_retrait_inclus' => ['type' => 'FLOAT', 'default' => 0, 'after' => 'frais_commission'],
        ]);

        $this->forge->addColumn('clients', [
            'credit_retrait' => ['type' => 'FLOAT', 'default' => 0, 'after' => 'solde'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', ['operateur_dest', 'frais_commission', 'frais_retrait_inclus']);
        $this->forge->dropColumn('clients', 'credit_retrait');
    }
}
