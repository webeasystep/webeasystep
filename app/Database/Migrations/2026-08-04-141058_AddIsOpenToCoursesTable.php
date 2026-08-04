<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsOpenToCoursesTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_open' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'active'
            ],
        ];
        $this->forge->addColumn('tb_courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_courses', 'is_open');
    }
}
