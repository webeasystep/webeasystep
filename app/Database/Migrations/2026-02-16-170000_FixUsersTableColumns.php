<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixUsersTableColumns extends Migration
{
    public function up()
    {
        $fields = [];
        
        if (!$this->db->fieldExists('verification_token', 'users')) {
            $fields['verification_token'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Email verification token'
            ];
        }

        if (!$this->db->fieldExists('email_verified_at', 'users')) {
            $fields['email_verified_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Email verification timestamp'
            ];
        }

        if (!$this->db->fieldExists('phone_verified_at', 'users')) {
            $fields['phone_verified_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Phone verification timestamp'
            ];
        }

        if (!$this->db->fieldExists('phone_verification_code', 'users')) {
            $fields['phone_verification_code'] = [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'comment' => 'SMS verification code'
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        // Don't drop as we don't want to lose data if rolled back, or conditional drop?
        // Safe to leave or explicit drop if we strictly manage it.
        $fieldsToDrop = [];
        if ($this->db->fieldExists('verification_token', 'users')) $fieldsToDrop[] = 'verification_token';
        if ($this->db->fieldExists('email_verified_at', 'users')) $fieldsToDrop[] = 'email_verified_at';
        if ($this->db->fieldExists('phone_verified_at', 'users')) $fieldsToDrop[] = 'phone_verified_at';
        if ($this->db->fieldExists('phone_verification_code', 'users')) $fieldsToDrop[] = 'phone_verification_code';

        if (!empty($fieldsToDrop)) {
            $this->forge->dropColumn('users', $fieldsToDrop);
        }
    }
}
