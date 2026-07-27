<?php

namespace Modules\Coupons\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsageLimitPerAccountToCoupons extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tb_coupons')) {
            return;
        }

        $fields = $this->db->getFieldData('tb_coupons');
        $existingColumns = array_map(static fn($field) => $field->name, $fields);

        if (in_array('usage_limit_per_account', $existingColumns, true)) {
            return;
        }

        $this->forge->addColumn('tb_coupons', [
            'usage_limit_per_account' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'usage_limit',
            ],
        ]);
    }

    public function down()
    {
        if ($this->db->tableExists('tb_coupons')) {
            $fields = $this->db->getFieldData('tb_coupons');
            $existingColumns = array_map(static fn($field) => $field->name, $fields);

            if (in_array('usage_limit_per_account', $existingColumns, true)) {
                $this->forge->dropColumn('tb_coupons', 'usage_limit_per_account');
            }
        }
    }
}
