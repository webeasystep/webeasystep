<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetaDescriptionToArticles extends Migration
{
    public function up()
    {
        // Add meta_description column to articles table
        $this->forge->addColumn('articles', [
            'meta_description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'slug',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('articles', 'meta_description');
    }
}
