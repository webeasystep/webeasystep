<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToUsersTable extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('mobile', 'users')) {
            $fields['mobile'] = [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true,
            ];
        }

        if (!$this->db->fieldExists('address', 'users')) {
            $fields['address'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        $fieldsToDrop = [];
        if ($this->db->fieldExists('mobile', 'users')) $fieldsToDrop[] = 'mobile';
        if ($this->db->fieldExists('address', 'users')) $fieldsToDrop[] = 'address';

        if (!empty($fieldsToDrop)) {
            $this->forge->dropColumn('users', $fieldsToDrop);
        }
    }
}
