<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserItemProgressTable extends Migration
{
    public function up()
    {
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
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_units table'
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_unit_items table'
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_enrollments table'
            ],
            'progress_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
                'comment' => 'Progress percentage (0-100)'
            ],
            'watch_time' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total watch time in seconds'
            ],
            'last_position' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Last video position in seconds'
            ],
            'is_completed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether item is completed'
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When item was completed'
            ],
            'first_accessed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When item was first accessed'
            ],
            'last_accessed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When item was last accessed'
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
        $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'tb_unit_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enrollment_id', 'tb_enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['user_id', 'item_id'], true, true, 'idx_user_item_progress_unique');
        $this->forge->addKey(['user_id', 'unit_id'], false, false, 'idx_user_item_progress_user_unit');
        $this->forge->addKey(['enrollment_id'], false, false, 'idx_user_item_progress_enrollment');
        $this->forge->addKey(['is_completed'], false, false, 'idx_user_item_progress_completed');
        $this->forge->createTable('tb_user_item_progress');
    }

    public function down()
    {
        $this->forge->dropTable('tb_user_item_progress');
    }
}