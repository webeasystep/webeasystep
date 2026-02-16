<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVideoIdToUnitsTable extends Migration
{
    public function up()
    {
        // Add video_id column to tb_units table
        $fields = [
            'video_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'unit_desc'
            ],
            'video_duration' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'video_id'
            ]
        ];
        
        $this->forge->addColumn('tb_units', $fields);
    }

    public function down()
    {
        // Remove video_id and video_duration columns from tb_units table
        $this->forge->dropColumn('tb_units', ['video_id', 'video_duration']);
    }
}