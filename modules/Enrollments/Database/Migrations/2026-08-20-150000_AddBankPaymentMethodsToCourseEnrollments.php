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
        $paymentMethodField = null;
        foreach ($fields as $field) {
            if ($field->name === 'payment_method') {
                $paymentMethodField = $field;
                break;
            }
        }

        if ($paymentMethodField !== null) {
            $this->db->query("
                ALTER TABLE `tb_course_enrollments`
                MODIFY `payment_method` ENUM('fawry','vodafone_cash','instapay','bank_transfer','credits','free','paypal','usdt','anb','stc_bank') NULL DEFAULT 'paypal'
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
