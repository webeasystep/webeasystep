<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceUsersTable extends Migration
{
    public function up()
    {
        // Add new columns to existing users table
        $fields = [
            'parent_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Parent/Guardian name for students under 18'
            ],
            'parent_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Parent/Guardian email for notifications'
            ],
            'parent_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Parent/Guardian phone number'
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Student birth date to determine if under 18'
            ],
            'credits' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'comment' => 'User credit balance'
            ],
            'email_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Email verification timestamp'
            ],
            'verification_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Email verification token'
            ],
            'phone_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Phone verification timestamp'
            ],
            'phone_verification_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'comment' => 'SMS verification code'
            ]
        ];
        
        $this->forge->addColumn('users', $fields);
        
        // Add indexes for better performance
        $this->forge->addKey(['email_verified_at'], false, false, 'idx_users_email_verified');
        $this->forge->addKey(['phone_verified_at'], false, false, 'idx_users_phone_verified');
        $this->forge->addKey(['parent_email'], false, false, 'idx_users_parent_email');
    }

    public function down()
    {
        // Drop added columns
        $this->forge->dropColumn('users', [
            'parent_name', 'parent_email', 'parent_phone', 'birth_date',
            'credits', 'email_verified_at', 'verification_token',
            'phone_verified_at', 'phone_verification_code'
        ]);
    }
}