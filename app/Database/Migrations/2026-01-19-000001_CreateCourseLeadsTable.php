<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseLeadsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'course_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Name of the course the user is interested in'
            ],
            'user_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Email address of the interested user'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('course_name', false, false, 'idx_course_leads_course');
        $this->forge->addKey('created_at', false, false, 'idx_course_leads_date');
        $this->forge->createTable('tb_course_leads');
    }

    public function down()
    {
        $this->forge->dropTable('tb_course_leads');
    }
}
