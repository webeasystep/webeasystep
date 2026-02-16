<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBillingSystem extends Migration
{
    public function up()
    {
        // Create tb_credit_transactions table
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
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['purchase', 'spend', 'refund'],
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed', 'cancelled'],
                'default' => 'pending',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reference_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'reference_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'created_at'], false, false, 'idx_user_transactions');
        $this->forge->addKey(['transaction_type', 'status'], false, false, 'idx_transaction_type');
        $this->forge->addKey(['reference_type', 'reference_id'], false, false, 'idx_reference');
        $this->forge->createTable('tb_credit_transactions');

        // Create tb_payment_requests table
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
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'bank_transfer',
            ],
            'proof_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'status'], false, false, 'idx_user_requests');
        $this->forge->addKey(['status', 'created_at'], false, false, 'idx_status_date');
        $this->forge->createTable('tb_payment_requests');
    }

    public function down()
    {
        $this->forge->dropTable('tb_payment_requests');
        $this->forge->dropTable('tb_credit_transactions');
    }
}