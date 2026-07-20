<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleToClientsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('clients', [
            'role' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'client', 'after' => 'telephone'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', 'role');
    }
}
