<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInstructorBioToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'instructor_bio' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'full_name',
            ],
            'avatar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'instructor_bio',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['instructor_bio', 'avatar']);
    }
}
