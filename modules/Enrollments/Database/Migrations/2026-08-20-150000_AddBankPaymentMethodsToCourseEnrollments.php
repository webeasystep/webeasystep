<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBankPaymentMethodsToCourseEnrollments extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tb_course_enrollments')) {
            return;
        }

        $fields = $this->db->getFieldData('tb_course_enrollments');
        $fieldNames = array_column($fields, 'name');

        if (in_array('payment_method', $fieldNames)) {
            $this->db->query("
                ALTER TABLE `tb_course_enrollments`
                MODIFY `payment_method` ENUM('fawry','vodafone_cash','instapay','bank_transfer','credits','free','paypal','usdt','anb','stc_bank') NULL DEFAULT 'paypal'
            ");
        }

        if (in_array('status', $fieldNames)) {
            $this->db->query("
                ALTER TABLE `tb_course_enrollments`
                MODIFY `status` ENUM('pending','approved','rejected','expired','refunded') NULL DEFAULT 'pending'
            ");
        }

        if (!in_array('refund_proof', $fieldNames)) {
            $this->db->query("
                ALTER TABLE `tb_course_enrollments`
                ADD COLUMN `refund_proof` VARCHAR(255) NULL AFTER `payment_proof`
            ");
        }

        if (!in_array('refunded_at', $fieldNames)) {
            $this->db->query("
                ALTER TABLE `tb_course_enrollments`
                ADD COLUMN `refunded_at` DATETIME NULL AFTER `approved_at`
            ");
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('tb_course_enrollments')) {
            return;
        }

        $this->db->query("
            ALTER TABLE `tb_course_enrollments`
            MODIFY `payment_method` ENUM('fawry','vodafone_cash','instapay','bank_transfer','credits','free','paypal','usdt') NULL DEFAULT 'paypal'
        ");
    }
}

