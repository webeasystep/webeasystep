<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMobileToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('mobile', 'users')) {
            $this->forge->addColumn('users', [
                'mobile' => [
                    'type' => 'VARCHAR',
                    'constraint' => 15,
                    'null' => true,
                    'after' => 'email' // Or wherever appropriate
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('mobile', 'users')) {
            $this->forge->dropColumn('users', 'mobile');
        }
    }
}
