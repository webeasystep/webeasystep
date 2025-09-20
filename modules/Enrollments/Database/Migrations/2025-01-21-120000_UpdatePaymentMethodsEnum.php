<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePaymentMethodsEnum extends Migration
{
    public function up()
    {
        // Update payment_method field to only allow instapay and vodafone_cash
        $this->forge->modifyColumn('tb_unit_enrollments', [
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['instapay', 'vodafone_cash'],
                'default' => 'instapay',
                'comment' => 'Payment method used - only instapay and vodafone_cash allowed'
            ]
        ]);

        // Also update tb_payment_attachments table if it exists
        if ($this->db->tableExists('tb_payment_attachments')) {
            $this->forge->modifyColumn('tb_payment_attachments', [
                'payment_method' => [
                    'type' => 'ENUM',
                    'constraint' => ['instapay', 'vodafone_cash'],
                    'default' => 'instapay',
                    'comment' => 'Payment method used - only instapay and vodafone_cash allowed'
                ]
            ]);
        }
    }

    public function down()
    {
        // Revert to original VARCHAR field to allow any payment method
        $this->forge->modifyColumn('tb_unit_enrollments', [
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'bank_transfer',
                'comment' => 'Payment method used'
            ]
        ]);

        // Also revert tb_payment_attachments table if it exists
        if ($this->db->tableExists('tb_payment_attachments')) {
            $this->forge->modifyColumn('tb_payment_attachments', [
                'payment_method' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'comment' => 'Payment method used'
                ]
            ]);
        }
    }
}