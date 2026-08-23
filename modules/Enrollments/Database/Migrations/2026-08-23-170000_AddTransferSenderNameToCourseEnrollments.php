<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTransferSenderNameToCourseEnrollments extends Migration
{
    public function up()
    {
        $fields = array_column($this->db->getFieldData('tb_course_enrollments'), 'name');

        if (!in_array('transfer_sender_name', $fields, true)) {
            $this->forge->addColumn('tb_course_enrollments', [
                'transfer_sender_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                    'after'      => 'payment_proof',
                ],
            ]);
        }
    }

    public function down()
    {
        $fields = array_column($this->db->getFieldData('tb_course_enrollments'), 'name');

        if (in_array('transfer_sender_name', $fields, true)) {
            $this->forge->dropColumn('tb_course_enrollments', 'transfer_sender_name');
        }
    }
}
