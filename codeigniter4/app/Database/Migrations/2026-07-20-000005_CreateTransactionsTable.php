<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'client_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'type_operation_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'montant'           => ['type' => 'FLOAT'],
            'frais'             => ['type' => 'FLOAT', 'default' => 0],
            'telephone_dest'    => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_operation_id', 'types_operations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
