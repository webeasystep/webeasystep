<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitEnrollmentsTable extends Migration
{
    public function up()
    {
        // Create tb_unit_enrollments table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'User who is purchasing units'
            ],
            'unit_ids' => [
                'type' => 'JSON',
                'comment' => 'Array of unit IDs being purchased'
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Total price for selected units'
            ],
            'payment_proof' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Path to payment proof image'
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'bank_transfer',
                'comment' => 'Payment method used'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
                'comment' => 'Enrollment status'
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Admin notes for approval/rejection'
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Admin user ID who processed the request'
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Timestamp when request was processed'
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->addKey(['user_id', 'status']);
        $this->forge->addKey('created_at');
        
        // Add foreign key constraint if users table exists
        if ($this->db->tableExists('users')) {
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        }
        
        $this->forge->createTable('tb_unit_enrollments');

        // Add indexes for performance
        $this->addOptimizationIndexes();
    }

    public function down()
    {
        $this->forge->dropTable('tb_unit_enrollments', true);
    }

    /**
     * Add performance optimization indexes
     */
    private function addOptimizationIndexes()
    {
        if ($this->db->tableExists('tb_unit_enrollments')) {
            try {
                // Index for getting pending enrollments
                $this->db->query('CREATE INDEX idx_unit_enrollments_pending ON tb_unit_enrollments (status, created_at)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
            
            try {
                // Index for user enrollment history
                $this->db->query('CREATE INDEX idx_unit_enrollments_user_status ON tb_unit_enrollments (user_id, status, created_at)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        }
    }
}