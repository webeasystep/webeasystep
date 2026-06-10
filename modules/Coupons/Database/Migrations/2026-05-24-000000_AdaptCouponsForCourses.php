<?php

namespace Modules\Coupons\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdaptCouponsForCourses extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('fd_coupons') && !$this->db->tableExists('tb_coupons')) {
            $this->forge->renameTable('fd_coupons', 'tb_coupons');
        }

        if (!$this->db->tableExists('tb_coupons')) {
            return;
        }

        $couponFields = $this->db->getFieldData('tb_coupons');
        $existingCouponColumns = array_map(static fn($field) => $field->name, $couponFields);

        $couponColumnsToAdd = [];

        if (!in_array('course_id', $existingCouponColumns, true)) {
            $couponColumnsToAdd['course_id'] = [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id',
            ];
        }

        if (!in_array('discount_type', $existingCouponColumns, true)) {
            $couponColumnsToAdd['discount_type'] = [
                'type' => 'ENUM',
                'constraint' => ['percentage', 'fixed'],
                'default' => 'percentage',
                'after' => 'coupon_code',
            ];
        }

        if (!in_array('discount_value', $existingCouponColumns, true)) {
            $couponColumnsToAdd['discount_value'] = [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
                'after' => 'discount_percentage',
            ];
        }

        if (!in_array('usage_limit_per_account', $existingCouponColumns, true)) {
            $couponColumnsToAdd['usage_limit_per_account'] = [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
                'after' => 'usage_limit',
            ];
        }

        if (!empty($couponColumnsToAdd)) {
            $this->forge->addColumn('tb_coupons', $couponColumnsToAdd);
        }

        if ($this->db->tableExists('tb_course_enrollments')) {
            $enrollmentFields = $this->db->getFieldData('tb_course_enrollments');
            $existingEnrollmentColumns = array_map(static fn($field) => $field->name, $enrollmentFields);

            $enrollmentColumnsToAdd = [];

            if (!in_array('coupon_id', $existingEnrollmentColumns, true)) {
                $enrollmentColumnsToAdd['coupon_id'] = [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'paid_amount',
                ];
            }

            if (!in_array('coupon_code', $existingEnrollmentColumns, true)) {
                $enrollmentColumnsToAdd['coupon_code'] = [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'coupon_id',
                ];
            }

            if (!in_array('coupon_discount_amount', $existingEnrollmentColumns, true)) {
                $enrollmentColumnsToAdd['coupon_discount_amount'] = [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0,
                    'after' => 'coupon_code',
                ];
            }

            if (!empty($enrollmentColumnsToAdd)) {
                $this->forge->addColumn('tb_course_enrollments', $enrollmentColumnsToAdd);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('tb_course_enrollments')) {
            $enrollmentFields = $this->db->getFieldData('tb_course_enrollments');
            $existingEnrollmentColumns = array_map(static fn($field) => $field->name, $enrollmentFields);
            $columnsToDrop = array_values(array_intersect(['coupon_id', 'coupon_code', 'coupon_discount_amount'], $existingEnrollmentColumns));

            if (!empty($columnsToDrop)) {
                $this->forge->dropColumn('tb_course_enrollments', $columnsToDrop);
            }
        }

        if ($this->db->tableExists('tb_coupons') && !$this->db->tableExists('fd_coupons')) {
            $this->forge->renameTable('tb_coupons', 'fd_coupons');
        }
    }
}
