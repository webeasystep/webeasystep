<?php

namespace Modules\Progress\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameEnrollmentIdToCourseEnrollmentId extends Migration
{
    public function up()
    {
        $fields = [
            'enrollment_id' => [
                'name' => 'course_enrollment_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ],
        ];
        $this->forge->modifyColumn('tb_user_item_progress', $fields);
    }

    public function down()
    {
        $fields = [
            'course_enrollment_id' => [
                'name' => 'enrollment_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ],
        ];
        $this->forge->modifyColumn('tb_user_item_progress', $fields);
    }
}
