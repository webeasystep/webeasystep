<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropAllForeignKeys extends Migration
{
    public function up()
    {
        // Drop all foreign key constraints from the database
        
        // Auth tables foreign keys
        $this->dropForeignKeyIfExists('auth_identities', 'auth_identities_user_id_foreign');
        $this->dropForeignKeyIfExists('auth_remember_tokens', 'auth_remember_tokens_user_id_foreign');
        $this->dropForeignKeyIfExists('auth_groups_users', 'auth_groups_users_user_id_foreign');
        $this->dropForeignKeyIfExists('auth_permissions_users', 'auth_permissions_users_group_id_foreign');
        $this->dropForeignKeyIfExists('auth_permissions_users', 'auth_permissions_users_user_id_foreign');
        
        // Course-related tables foreign keys
        $this->dropForeignKeyIfExists('tb_units', 'tb_units_section_id_foreign');
        $this->dropForeignKeyIfExists('tb_user_unit_progress', 'fk_user_unit_progress_user');
        $this->dropForeignKeyIfExists('tb_user_unit_progress', 'fk_user_unit_progress_unit');
        $this->dropForeignKeyIfExists('tb_user_unit_progress', 'tb_user_unit_progress_user_id_foreign');
        $this->dropForeignKeyIfExists('tb_user_unit_progress', 'tb_user_unit_progress_unit_id_foreign');
        $this->dropForeignKeyIfExists('tb_user_unit_progress', 'tb_user_unit_progress_enrollment_id_foreign');
        
        // Billing tables foreign keys
        $this->dropForeignKeyIfExists('tb_credit_transactions', 'tb_credit_transactions_user_id_foreign');
        $this->dropForeignKeyIfExists('tb_credit_transactions', 'tb_credit_transactions_processed_by_foreign');
        $this->dropForeignKeyIfExists('tb_payment_requests', 'tb_payment_requests_user_id_foreign');
        $this->dropForeignKeyIfExists('tb_payment_requests', 'tb_payment_requests_processed_by_foreign');
        
        // Tracking tables foreign keys
        $this->dropForeignKeyIfExists('tb_login_logs', 'tb_login_logs_user_id_foreign');
        $this->dropForeignKeyIfExists('tb_email_logs', 'tb_email_logs_user_id_foreign');
        
        // Additional potential foreign keys
        $this->dropForeignKeyIfExists('tb_enrollments', 'tb_enrollments_user_id_foreign');
        $this->dropForeignKeyIfExists('tb_enrollments', 'tb_enrollments_course_id_foreign');
        $this->dropForeignKeyIfExists('sections', 'sections_course_id_foreign');
        $this->dropForeignKeyIfExists('tb_videos', 'tb_videos_unit_id_foreign');
        $this->dropForeignKeyIfExists('tb_videos', 'tb_videos_section_id_foreign');
        $this->dropForeignKeyIfExists('tb_progress', 'tb_progress_user_id_foreign');
        $this->dropForeignKeyIfExists('tb_progress', 'tb_progress_course_id_foreign');
        
        echo "All foreign key constraints have been processed.\n";
    }

    public function down()
    {
        // This migration is irreversible as we're removing foreign key constraints
        // To restore foreign keys, you would need to run the original migration files
        echo "This migration cannot be reversed. Foreign key constraints have been permanently removed.\n";
    }
    
    /**
     * Helper method to drop foreign key constraint if it exists
     */
    private function dropForeignKeyIfExists(string $tableName, string $constraintName)
    {
        try {
            // Check if the constraint exists before trying to drop it
            $query = "SELECT COUNT(*) as count FROM information_schema.KEY_COLUMN_USAGE 
                     WHERE CONSTRAINT_NAME = ? AND TABLE_NAME = ? AND TABLE_SCHEMA = DATABASE()";
            
            $result = $this->db->query($query, [$constraintName, $tableName]);
            $row = $result->getRow();
            
            if ($row && $row->count > 0) {
                $this->db->query("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$constraintName}`");
                echo "Dropped foreign key constraint: {$constraintName} from table: {$tableName}\n";
            } else {
                echo "Foreign key constraint {$constraintName} does not exist in table {$tableName}\n";
            }
        } catch (\Exception $e) {
            // Log the error but continue with other constraints
            echo "Error dropping foreign key {$constraintName} from {$tableName}: " . $e->getMessage() . "\n";
        }
    }
}