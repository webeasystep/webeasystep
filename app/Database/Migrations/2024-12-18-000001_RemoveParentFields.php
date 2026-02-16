<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Remove parent/guardian fields from users table
 * These fields are no longer needed as per updated requirements
 */
class RemoveParentFields extends Migration
{
    public function up()
    {
        // Check if columns exist before dropping
        $forge = \Config\Database::forge();
        
        // Drop columns if they exist
        $columnsToRemove = ['parent_name', 'parent_email', 'parent_phone', 'birth_date'];
        
        foreach ($columnsToRemove as $column) {
            if ($this->db->fieldExists($column, 'users')) {
                $forge->dropColumn('users', $column);
            }
        }
    }

    public function down()
    {
        // Re-add the columns if needed
        $fields = [
            'parent_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'mobile'
            ],
            'parent_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'parent_name'
            ],
            'parent_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'parent_email'
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'parent_phone'
            ],
        ];
        
        $this->forge->addColumn('users', $fields);
    }
}
