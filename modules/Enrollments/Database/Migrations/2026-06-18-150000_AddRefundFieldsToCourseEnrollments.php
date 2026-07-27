<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRefundFieldsToCourseEnrollments extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tb_course_enrollments')) {
            return;
        }

        $fields = $this->db->getFieldData('tb_course_enrollments');
        $existingColumns = array_map(static fn($field) => $field->name, $fields);

        $columnsToAdd = [];

        if (!in_array('refund_proof', $existingColumns, true)) {
            $columnsToAdd['refund_proof'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'payment_proof',
            ];
        }

        if (!in_array('refunded_at', $existingColumns, true)) {
            $columnsToAdd['refunded_at'] = [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'approved_at',
            ];
        }

        if (!empty($columnsToAdd)) {
            $this->forge->addColumn('tb_course_enrollments', $columnsToAdd);
        }

        $statusField = null;
        foreach ($fields as $field) {
            if ($field->name === 'status') {
                $statusField = $field;
                break;
            }
        }

        if ($statusField !== null && str_contains(strtolower($statusField->type), "enum('pending','approved','rejected','expired')")) {
            $this->db->query("
                ALTER TABLE `tb_course_enrollments`
                MODIFY `status` ENUM('pending','approved','rejected','expired','refunded') NULL DEFAULT 'pending'
            ");
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('tb_course_enrollments')) {
            return;
        }

        $fields = $this->db->getFieldData('tb_course_enrollments');
        $existingColumns = array_map(static fn($field) => $field->name, $fields);
        $columnsToDrop = array_values(array_intersect(['refund_proof', 'refunded_at'], $existingColumns));

        if (!empty($columnsToDrop)) {
            $this->forge->dropColumn('tb_course_enrollments', $columnsToDrop);
        }

        $statusField = null;
        foreach ($fields as $field) {
            if ($field->name === 'status') {
                $statusField = $field;
                break;
            }
        }

        if ($statusField !== null && str_contains(strtolower($statusField->type), 'refunded')) {
            $this->db->query("
                ALTER TABLE `tb_course_enrollments`
                MODIFY `status` ENUM('pending','approved','rejected','expired') NULL DEFAULT 'pending'
            ");
        }
    }
}
