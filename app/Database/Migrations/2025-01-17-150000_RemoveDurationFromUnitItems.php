<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveDurationFromUnitItems extends Migration
{
    public function up()
    {
        // Remove duration column since it's now stored in metadata JSON
        $this->forge->dropColumn('tb_unit_items', 'duration');
    }

    public function down()
    {
        // Add duration column back if needed
        $this->forge->addColumn('tb_unit_items', [
            'duration' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'description'
            ]
        ]);
    }
}