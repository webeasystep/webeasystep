<?php

namespace Modules\Coupons\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'coupon_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'discount_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
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
        $this->forge->addKey('active');
        $this->forge->addKey('end_date');
        $this->forge->createTable('fd_coupons');

        $this->forge->addColumn('fd_cart', [
            'coupon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'quantity',
            ],
            'coupon_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'coupon_id',
            ],
        ]);

        $this->forge->addColumn('fd_orders', [
            'coupon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'discount_amount',
            ],
            'coupon_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'coupon_id',
            ],
            'coupon_discount_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0,
                'after' => 'coupon_code',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fd_orders', ['coupon_id', 'coupon_code', 'coupon_discount_amount']);
        $this->forge->dropColumn('fd_cart', ['coupon_id', 'coupon_code']);
        $this->forge->dropTable('fd_coupons');
    }
}
