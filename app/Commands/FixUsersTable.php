<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixUsersTable extends BaseCommand
{
    protected $group       = 'Fixers';
    protected $name        = 'fix:users';
    protected $description = 'Adds missing columns to users table';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        $columnsToAdd = [];

        // Check verification_token
        if (!$db->fieldExists('verification_token', 'users')) {
            $columnsToAdd['verification_token'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email'
            ];
        }

        // Check email_verified_at
        if (!$db->fieldExists('email_verified_at', 'users')) {
            $columnsToAdd['email_verified_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'verification_token'
            ];
        }

        // Check phone_verified_at
        if (!$db->fieldExists('phone_verified_at', 'users')) {
            $columnsToAdd['phone_verified_at'] = [
                'type' => 'DATETIME',
                'null' => true
            ];
        }
        
        // Check phone_verification_code
        if (!$db->fieldExists('phone_verification_code', 'users')) {
            $columnsToAdd['phone_verification_code'] = [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true
            ];
        }

        if (!empty($columnsToAdd)) {
            $forge->addColumn('users', $columnsToAdd);
            CLI::write('Added ' . count($columnsToAdd) . ' columns to users table.', 'green');
            foreach (array_keys($columnsToAdd) as $col) {
                CLI::write(" - $col", 'yellow');
            }
        } else {
            CLI::write('No missing columns found in users table.', 'green');
        }
    }
}
