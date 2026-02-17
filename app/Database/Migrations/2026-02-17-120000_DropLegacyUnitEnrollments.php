<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropLegacyUnitEnrollments extends Migration
{
    public function up()
    {
        // Alter tb_user_item_progress column
        // Check if column exists first to avoid errors if run multiple times
        if ($this->db->fieldExists('enrollment_id', 'tb_user_item_progress')) {
            $this->forge->modifyColumn('tb_user_item_progress', [
                'enrollment_id' => [
                    'name' => 'course_enrollment_id',
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
            ]);
        }

        // Drop tb_unit_enrollments
        $this->forge->dropTable('tb_unit_enrollments', true);
    }

    public function down()
    {
        // Recreate tb_unit_enrollments (minimal schema for rollback)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'unit_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'default' => 'pending'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_unit_enrollments', true);

        // Revert tb_user_item_progress
        if ($this->db->fieldExists('course_enrollment_id', 'tb_user_item_progress')) {
            $this->forge->modifyColumn('tb_user_item_progress', [
                'course_enrollment_id' => [
                    'name' => 'enrollment_id',
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
            ]);
        }
    }
}
