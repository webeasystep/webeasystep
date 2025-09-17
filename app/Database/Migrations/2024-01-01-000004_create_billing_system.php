<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBillingSystem extends Migration
{
    public function up()
    {
        // Create credit transactions table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to users table'
            ],
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['credit_purchase', 'course_enrollment', 'refund', 'admin_adjustment'],
                'comment' => 'Type of credit transaction'
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Transaction amount (positive for credits added, negative for spent)'
            ],
            'balance_before' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'User credit balance before transaction'
            ],
            'balance_after' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'User credit balance after transaction'
            ],
            'reference_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Type of related entity (course, payment, etc.)'
            ],
            'reference_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'ID of related entity'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Transaction description'
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Admin user who processed the transaction'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('processed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addKey(['user_id', 'created_at'], false, false, 'idx_credit_transactions_user_date');
        $this->forge->addKey(['transaction_type'], false, false, 'idx_credit_transactions_type');
        $this->forge->addKey(['reference_type', 'reference_id'], false, false, 'idx_credit_transactions_reference');
        $this->forge->createTable('tb_credit_transactions');
        
        // Create payment requests table for manual top-ups
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to users table'
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Requested credit amount'
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Payment method used'
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Payment reference number or transaction ID'
            ],
            'proof_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Path to payment proof image'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'cancelled'],
                'default' => 'pending',
                'comment' => 'Payment request status'
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
                'comment' => 'Admin user who processed the request'
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When request was processed'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('processed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addKey(['user_id', 'status'], false, false, 'idx_payment_requests_user_status');
        $this->forge->addKey(['status', 'created_at'], false, false, 'idx_payment_requests_status_date');
        $this->forge->createTable('tb_payment_requests');
        
        // Enhance existing tb_enrollments table
        $enrollmentFields = [
            'credits_used' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Credits used for enrollment'
            ],
            'enrollment_method' => [
                'type' => 'ENUM',
                'constraint' => ['credits', 'payment', 'free', 'admin'],
                'default' => 'payment',
                'comment' => 'How the enrollment was processed'
            ]
        ];
        
        $this->forge->addColumn('tb_enrollments', $enrollmentFields);
    }

    public function down()
    {
        // Drop added columns from enrollments
        $this->forge->dropColumn('tb_enrollments', ['credits_used', 'enrollment_method']);
        
        // Drop tables
        $this->forge->dropTable('tb_payment_requests');
        $this->forge->dropTable('tb_credit_transactions');
    }
}