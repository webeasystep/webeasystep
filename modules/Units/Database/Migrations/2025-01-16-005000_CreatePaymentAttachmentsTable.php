<?php

namespace Modules\Units\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentAttachmentsTable extends Migration
{
    public function up()
    {
        // Create tb_payment_attachments table
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
            'unit_ids' => [
                'type' => 'JSON',
                'comment' => 'Array of unit IDs being purchased'
            ],
            'total_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Total price for selected units'
            ],
            'payment_attachment' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Path to payment proof attachment'
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Payment method used'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
                'comment' => 'Payment verification status'
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Admin notes for approval/rejection'
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Admin user ID who approved/rejected'
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Timestamp of approval/rejection'
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
        
        // Add foreign key constraint if users table exists
        if ($this->db->tableExists('users')) {
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        }
        
        $this->forge->createTable('tb_payment_attachments');

        // Create tb_unit_purchases table for tracking individual unit access
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
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'payment_attachment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to payment attachment'
            ],
            'price_paid' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Price paid for this specific unit'
            ],
            'access_granted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 = Access granted, 0 = No access'
            ],
            'access_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When access expires (null = lifetime)'
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
        $this->forge->addKey(['user_id', 'unit_id'], false, true); // Unique constraint
        $this->forge->addKey('user_id');
        $this->forge->addKey('unit_id');
        $this->forge->addKey('payment_attachment_id');
        $this->forge->addKey('access_granted');
        
        // Add foreign key constraints
        if ($this->db->tableExists('users')) {
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        }
        if ($this->db->tableExists('tb_units')) {
            $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'CASCADE');
        }
        $this->forge->addForeignKey('payment_attachment_id', 'tb_payment_attachments', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('tb_unit_purchases');

        // Add unit pricing fields to tb_units table
        if ($this->db->tableExists('tb_units')) {
            $fields = [
                'unit_price' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'null' => true,
                    'comment' => 'Price for individual unit purchase'
                ],
                'is_purchasable' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                    'comment' => '1 = Can be purchased individually, 0 = Course only'
                ]
            ];
            
            $this->forge->addColumn('tb_units', $fields);
        }
    }

    public function down()
    {
        // Remove added columns from tb_units
        if ($this->db->tableExists('tb_units')) {
            $this->forge->dropColumn('tb_units', ['unit_price', 'is_purchasable']);
        }
        
        // Drop tables in reverse order
        $this->forge->dropTable('tb_unit_purchases', true);
        $this->forge->dropTable('tb_payment_attachments', true);
    }
}