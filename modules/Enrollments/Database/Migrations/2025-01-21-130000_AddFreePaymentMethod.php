<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFreePaymentMethod extends Migration
{
    public function up()
    {
        // Update tb_unit_enrollments table to add 'free' to payment_method enum
        $this->db->query("ALTER TABLE tb_unit_enrollments MODIFY COLUMN payment_method ENUM('instapay', 'vodafone_cash', 'free') NOT NULL DEFAULT 'instapay'");
        
        // Update tb_payment_attachments table to add 'free' to payment_method enum
        $this->db->query("ALTER TABLE tb_payment_attachments MODIFY COLUMN payment_method ENUM('instapay', 'vodafone_cash', 'free') NOT NULL DEFAULT 'instapay'");
        
        // Add index for free enrollments for better performance
        try {
            $this->db->query("CREATE INDEX idx_unit_enrollments_free ON tb_unit_enrollments (payment_method, status) WHERE payment_method = 'free'");
        } catch (\Exception $e) {
            // Index might already exist or not supported, ignore
        }
    }

    public function down()
    {
        // Remove the index first
        try {
            $this->db->query("DROP INDEX idx_unit_enrollments_free ON tb_unit_enrollments");
        } catch (\Exception $e) {
            // Index might not exist, ignore
        }
        
        // Revert tb_unit_enrollments table payment_method enum to original values
        $this->db->query("ALTER TABLE tb_unit_enrollments MODIFY COLUMN payment_method ENUM('instapay', 'vodafone_cash') NOT NULL DEFAULT 'instapay'");
        
        // Revert tb_payment_attachments table payment_method enum to original values  
        $this->db->query("ALTER TABLE tb_payment_attachments MODIFY COLUMN payment_method ENUM('instapay', 'vodafone_cash') NOT NULL DEFAULT 'instapay'");
    }
}