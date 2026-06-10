<?php

namespace Modules\Coupons\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsageLimitPerAccountToCoupons extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fd_coupons', [
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
        $this->forge->dropColumn('fd_coupons', 'usage_limit_per_account');
    }
}
