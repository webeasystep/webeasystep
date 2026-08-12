<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTelegramLinkToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'telegram_link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('tb_courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tb_courses', 'telegram_link');
    }
}
