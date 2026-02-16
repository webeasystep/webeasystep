<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReviseEnrollmentsForUnits extends Migration
{
    public function up()
    {
        // First, backup existing data if needed
        // Note: This migration assumes you want to completely restructure for units
        
        // Drop existing foreign keys and indexes
        if ($this->db->DBDriver === 'MySQLi') {
            // Drop foreign keys if they exist
            $this->db->disableForeignKeyChecks();
            
            // Drop existing indexes
            $this->forge->dropKey('tb_enrollments', 'user_course_unique');
            $this->forge->dropKey('tb_enrollments', 'idx_user_status');
            $this->forge->dropKey('tb_enrollments', 'idx_course_status');
            $this->forge->dropKey('tb_enrollments', 'idx_enrolled_date');
            $this->forge->dropKey('tb_enrollments', 'idx_completion');
        }
        
        // Remove unrelated fields for unit enrollment
        $fieldsToRemove = [
            'course_id',              // Not needed for unit enrollment
            'proof_image',            // Payment-related, not core enrollment
            'credits_used',           // Billing-related, not core enrollment
            'enrollment_method',      // Process-related, not essential
            'progress_percentage',    // Should be tracked separately
            'last_accessed_at',       // Activity tracking, not core enrollment
            'certificate_issued',     // Achievement-related, not core enrollment
            'certificate_issued_at',  // Achievement-related, not core enrollment
            'notes'                   // Administrative notes, not essential
        ];
        
        // Drop columns that exist
        foreach ($fieldsToRemove as $field) {
            if ($this->db->fieldExists($field, 'tb_enrollments')) {
                $this->forge->dropColumn('tb_enrollments', $field);
            }
        }
        
        // Add unit-specific fields
        $newFields = [
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_units table'
            ],
            'enrollment_date' => [
                'type' => 'DATE',
                'comment' => 'Date when student enrolled in unit'
            ]
        ];
        
        $this->forge->addColumn('tb_enrollments', $newFields);
        
        // Modify existing fields for better unit enrollment support
        $this->forge->modifyColumn('tb_enrollments', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['enrolled', 'completed', 'cancelled', 'suspended'],
                'default' => 'enrolled',
                'comment' => 'Unit enrollment status'
            ],
            'enrolled_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
                'comment' => 'When student enrolled in unit'
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When student completed the unit'
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
                'comment' => 'Last update timestamp'
            ]
        ]);
        
        // Add new indexes for unit enrollment
        $this->forge->addKey(['user_id', 'unit_id'], false, true, 'user_unit_unique');
        $this->forge->addKey(['user_id', 'status'], false, false, 'idx_user_status');
        $this->forge->addKey(['unit_id', 'status'], false, false, 'idx_unit_status');
        $this->forge->addKey(['enrollment_date'], false, false, 'idx_enrollment_date');
        $this->forge->addKey(['status', 'completed_at'], false, false, 'idx_completion');
        
        // Add foreign keys
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'CASCADE');
        
        if ($this->db->DBDriver === 'MySQLi') {
            $this->db->enableForeignKeyChecks();
        }
    }
    
    public function down()
    {
        // This is a destructive migration - down() would need careful consideration
        // For safety, we'll just drop the new fields and indexes
        
        if ($this->db->DBDriver === 'MySQLi') {
            $this->db->disableForeignKeyChecks();
        }
        
        // Drop new indexes
        $this->forge->dropKey('tb_enrollments', 'user_unit_unique');
        $this->forge->dropKey('tb_enrollments', 'idx_user_status');
        $this->forge->dropKey('tb_enrollments', 'idx_unit_status');
        $this->forge->dropKey('tb_enrollments', 'idx_enrollment_date');
        $this->forge->dropKey('tb_enrollments', 'idx_completion');
        
        // Drop new columns
        $this->forge->dropColumn('tb_enrollments', ['unit_id', 'enrollment_date']);
        
        // Note: Restoring the original structure would require
        // careful data migration and is beyond the scope of this down() method
        
        if ($this->db->DBDriver === 'MySQLi') {
            $this->db->enableForeignKeyChecks();
        }
    }
}