<?php

namespace Modules\Courses\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsFundamentalToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'is_fundamental' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
        ];
        $this->forge->addColumn('tb_courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_courses', 'is_fundamental');
    }
}
