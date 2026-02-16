<?php

namespace Modules\Units\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveThumbnailColumn extends Migration
{
    public function up()
    {
        // Remove video_thumbnail column from tb_unit_items table
        if ($this->db->tableExists('tb_unit_items')) {
            // Check if column exists before dropping
            if ($this->db->fieldExists('video_thumbnail', 'tb_unit_items')) {
                $this->forge->dropColumn('tb_unit_items', 'video_thumbnail');
            }
        }
    }

    public function down()
    {
        // Add back video_thumbnail column
        if ($this->db->tableExists('tb_unit_items')) {
            $this->forge->addColumn('tb_unit_items', [
                'video_thumbnail' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                    'null' => true,
                    'after' => 'video_duration'
                ]
            ]);
        }
    }
}