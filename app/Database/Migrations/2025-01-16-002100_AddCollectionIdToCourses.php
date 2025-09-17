<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCollectionIdToCourses extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tb_courses', [
            'collection_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Collection ID for video embedding'
            ],
            'intro_video_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Intro video ID for course preview'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_courses', ['collection_id', 'intro_video_id']);
    }
}