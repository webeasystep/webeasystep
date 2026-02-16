<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProgressTracking extends Migration
{
    public function up()
    {
        // Create user unit progress table
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
                'comment' => 'Reference to users table'
            ],
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_units table'
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_enrollments table'
            ],
            'progress_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
                'comment' => 'Progress percentage (0-100)'
            ],
            'watch_time' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total watch time in seconds'
            ],
            'last_position' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Last video position in seconds'
            ],
            'is_completed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether unit is completed'
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When unit was completed'
            ],
            'first_accessed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When unit was first accessed'
            ],
            'last_accessed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When unit was last accessed'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enrollment_id', 'tb_enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['user_id', 'unit_id'], true, true, 'idx_user_unit_progress_unique');
        $this->forge->addKey(['enrollment_id'], false, false, 'idx_user_unit_progress_enrollment');
        $this->forge->addKey(['is_completed'], false, false, 'idx_user_unit_progress_completed');
        $this->forge->createTable('tb_user_unit_progress');
        
        // Create login logs table
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
        
        // Create email logs table
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
                'comment' => 'Reference to users table'
            ],
            'recipient_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Email address of recipient'
            ],
            'email_type' => [
                'type' => 'ENUM',
                'constraint' => ['verification', 'password_reset', 'parent_notification', 'course_completion', 'system_notification'],
                'comment' => 'Type of email sent'
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Email subject line'
            ],
            'template_used' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Email template identifier'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['sent', 'failed', 'queued'],
                'default' => 'queued',
                'comment' => 'Email delivery status'
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Error message if sending failed'
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When email was successfully sent'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['user_id', 'email_type'], false, false, 'idx_email_logs_user_type');
        $this->forge->addKey(['status', 'created_at'], false, false, 'idx_email_logs_status_date');
        $this->forge->addKey(['email_type', 'sent_at'], false, false, 'idx_email_logs_type_sent');
        $this->forge->createTable('tb_email_logs');
    }

    public function down()
    {
        $this->forge->dropTable('tb_email_logs');
        $this->forge->dropTable('tb_login_logs');
        $this->forge->dropTable('tb_user_unit_progress');
    }
}