<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserUnitProgressTable extends Migration
{
    public function up()
    {
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
            'progress_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'comment'    => 'Progress percentage (0.00 to 100.00)'
            ],
            'watch_time_seconds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'comment'    => 'Total time spent watching in seconds'
            ],
            'last_position_seconds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'comment'    => 'Last playback position in seconds'
            ],
            'is_completed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1 if unit is completed, 0 otherwise'
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Timestamp when unit was completed'
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
        $this->forge->addKey(['user_id', 'unit_id'], false, true); // Unique composite key
        $this->forge->addKey('user_id');
        $this->forge->addKey('unit_id');
        $this->forge->addKey('is_completed');
        $this->forge->addKey('completed_at');
        $this->forge->addKey('updated_at');

        $this->forge->createTable('tb_user_unit_progress');

        // Add foreign key constraints
        $this->db->query('ALTER TABLE `tb_user_unit_progress` 
                         ADD CONSTRAINT `fk_user_unit_progress_user` 
                         FOREIGN KEY (`user_id`) REFERENCES `tb_users`(`id`) 
                         ON DELETE CASCADE ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `tb_user_unit_progress` 
                         ADD CONSTRAINT `fk_user_unit_progress_unit` 
                         FOREIGN KEY (`unit_id`) REFERENCES `tb_units`(`id`) 
                         ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key constraints first
        $this->db->query('ALTER TABLE `tb_user_unit_progress` DROP FOREIGN KEY `fk_user_unit_progress_user`');
        $this->db->query('ALTER TABLE `tb_user_unit_progress` DROP FOREIGN KEY `fk_user_unit_progress_unit`');
        
        $this->forge->dropTable('tb_user_unit_progress');
    }
}