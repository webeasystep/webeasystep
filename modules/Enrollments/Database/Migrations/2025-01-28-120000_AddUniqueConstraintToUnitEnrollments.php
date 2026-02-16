<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueConstraintToUnitEnrollments extends Migration
{
    public function up()
    {
        // First, remove any existing duplicate records
        $this->db->query("
            DELETE e1 FROM tb_unit_enrollments e1
            INNER JOIN tb_unit_enrollments e2 
            WHERE e1.id > e2.id 
            AND e1.user_id = e2.user_id 
            AND e1.unit_id = e2.unit_id
        ");

        // Add unique constraint on user_id + unit_id combination using raw SQL
        $this->db->query("ALTER TABLE tb_unit_enrollments ADD UNIQUE KEY unique_user_unit (user_id, unit_id)");
    }

    public function down()
    {
        // Remove the unique constraint
        $this->db->query("ALTER TABLE tb_unit_enrollments DROP INDEX unique_user_unit");
    }
}