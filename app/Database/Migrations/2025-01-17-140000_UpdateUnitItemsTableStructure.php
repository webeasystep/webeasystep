<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUnitItemsTableStructure extends Migration
{
    public function up()
    {
        // Add metadata column
        $this->forge->addColumn('tb_unit_items', [
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Additional data including video_thumbnail, video_title, video_duration, collection_id, file size, passing score, etc.',
                'after' => 'is_active'
            ]
        ]);
        
        // Add collection_id column for video collection management
        $this->forge->addColumn('tb_unit_items', [
            'collection_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Video collection identifier for content organization',
                'after' => 'video_thumbnail'
            ]
        ]);
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('tb_unit_items', ['metadata', 'collection_id']);
    }
}