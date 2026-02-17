<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropLegacyUnitEnrollments extends Migration
{
    public function up()
    {
        // Drop the legacy tb_unit_enrollments table if it exists
        $this->forge->dropTable('tb_unit_enrollments', true);
    }

    public function down()
    {
        // Re-create the table if rolling back (basic structure for safety)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'course_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'cancelled'],
                'default'    => 'pending',
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'paid_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'payment_proof' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'enrolled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_unit_enrollments');
    }
}
