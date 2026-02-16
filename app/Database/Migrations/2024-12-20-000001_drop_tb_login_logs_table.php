<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropTbLoginLogsTable extends Migration
{
    public function up()
    {
        // Drop the tb_login_logs table as Shield uses auth_logins instead
        if ($this->db->tableExists('tb_login_logs')) {
            $this->forge->dropTable('tb_login_logs');
        }
    }

    public function down()
    {
        // Recreate tb_login_logs table if needed for rollback
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Reference to users table (null for failed attempts)'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Email used for login attempt'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'comment' => 'IP address of login attempt'
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Browser user agent'
            ],
            'login_status' => [
                'type' => 'ENUM',
                'constraint' => ['success', 'failed', 'blocked'],
                'comment' => 'Login attempt result'
            ],
            'failure_reason' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Reason for login failure'
            ],
            'session_id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => true,
                'comment' => 'Session ID for successful logins'
            ],
            'logout_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When user logged out'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['user_id', 'created_at'], false, false, 'idx_login_logs_user_date');
        $this->forge->addKey(['ip_address', 'created_at'], false, false, 'idx_login_logs_ip_date');
        $this->forge->addKey(['login_status'], false, false, 'idx_login_logs_status');
        $this->forge->createTable('tb_login_logs');
    }
}