<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitItemsTable extends Migration
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
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'item_type' => [
                'type'       => 'ENUM',
                'constraint' => ['video', 'quiz', 'page'],
                'null'       => false,
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'video_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'video_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'video_duration' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'video_thumbnail' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('unit_id');
        $this->forge->addKey('item_type');
        $this->forge->addKey('sort_order');
        
        // Foreign key constraints
        $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('tb_unit_items');
    }

    public function down()
    {
        $this->forge->dropTable('tb_unit_items');
    }
}