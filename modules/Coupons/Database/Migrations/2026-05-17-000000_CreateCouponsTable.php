<?php

namespace Modules\Coupons\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('tb_coupons')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'coupon_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'discount_type' => [
                'type' => 'ENUM',
                'constraint' => ['percentage', 'fixed'],
                'default' => 'percentage',
            ],
            'discount_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0,
            ],
            'discount_value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'usage_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'used_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
            ],
            'active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'is_deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('coupon_code');
        $this->forge->addKey('course_id');
        $this->forge->addKey('active');
        $this->forge->addKey('end_date');
        $this->forge->createTable('tb_coupons');

        if ($this->db->tableExists('tb_course_enrollments')) {
            $fields = $this->db->getFieldData('tb_course_enrollments');
            $existingColumns = array_map(static fn($field) => $field->name, $fields);

            $columnsToAdd = [];

            if (!in_array('coupon_id', $existingColumns, true)) {
                $columnsToAdd['coupon_id'] = [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'paid_amount',
                ];
            }

            if (!in_array('coupon_code', $existingColumns, true)) {
                $columnsToAdd['coupon_code'] = [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'coupon_id',
                ];
            }

            if (!in_array('coupon_discount_amount', $existingColumns, true)) {
                $columnsToAdd['coupon_discount_amount'] = [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0,
                    'after' => 'coupon_code',
                ];
            }

            if (!empty($columnsToAdd)) {
                $this->forge->addColumn('tb_course_enrollments', $columnsToAdd);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('tb_course_enrollments')) {
            $fields = $this->db->getFieldData('tb_course_enrollments');
            $existingColumns = array_map(static fn($field) => $field->name, $fields);
            $columnsToDrop = array_values(array_intersect(['coupon_id', 'coupon_code', 'coupon_discount_amount'], $existingColumns));

            if (!empty($columnsToDrop)) {
                $this->forge->dropColumn('tb_course_enrollments', $columnsToDrop);
            }
        }

        $this->forge->dropTable('tb_coupons', true);
    }
}
