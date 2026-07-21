<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBaremesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cle' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'valeur' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
             'created_at' => ['type' => 'DATETIME', 'NULL' =>  True],
             'update_at' => ['type' => 'DATETIME', 'NULL' =>  True],
        ]);
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('baremes');
    }
}
