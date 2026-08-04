<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MergeCourseLeadsAndRequests extends Migration
{
    public function up()
    {
        // 1. Make college_id and department_id nullable in tb_course_requests
        $this->db->query("ALTER TABLE `tb_course_requests` MODIFY `college_id` INT(10) UNSIGNED NULL");
        $this->db->query("ALTER TABLE `tb_course_requests` MODIFY `department_id` INT(10) UNSIGNED NULL");

        // 2. Migrate existing data from tb_course_leads if it exists
        if ($this->db->tableExists('tb_course_leads')) {
            $leads = $this->db->table('tb_course_leads')->get()->getResultArray();
            if (!empty($leads)) {
                $batch = [];
                foreach ($leads as $lead) {
                    $batch[] = [
                        'course_name_code' => $lead['course_name'],
                        'contact_info'     => $lead['user_email'],
                        'notify_me'        => 1,
                        'college_id'       => null,
                        'department_id'    => null,
                        'status'           => 'pending',
                        'created_at'       => $lead['created_at'],
                        'updated_at'       => $lead['created_at'],
                    ];
                }
                $this->db->table('tb_course_requests')->insertBatch($batch);
            }
            // 3. Drop the old table
            $this->forge->dropTable('tb_course_leads');
        }
    }

    public function down()
    {
        // Not easily reversible since data is merged, but we can recreate the table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'course_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('course_name');
        $this->forge->addKey('created_at');
        $this->forge->createTable('tb_course_leads');
    }
}
