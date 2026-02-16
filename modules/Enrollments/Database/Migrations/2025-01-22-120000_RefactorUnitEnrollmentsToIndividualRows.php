<?php

namespace Modules\Enrollments\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefactorUnitEnrollmentsToIndividualRows extends Migration
{
    public function up()
    {
        // First, let's backup existing data before making changes
        $this->backupExistingData();

        // Create a temporary table with the new structure
        $this->createTemporaryTable();

        // Migrate data from old structure to new structure
        $this->migrateData();

        // Drop the old table and rename the temporary table
        $this->replaceTable();

        // Add indexes for better performance
        $this->addIndexes();
    }

    public function down()
    {
        // Revert back to the original JSON structure
        $this->forge->dropTable('tb_unit_enrollments', true);

        // Recreate the original table structure
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'unit_ids' => [
                'type' => 'JSON',
                'comment' => 'JSON array of unit IDs',
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
            ],
            'payment_proof' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['instapay', 'vodafone_cash', 'free'],
                'default' => 'instapay',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->createTable('tb_unit_enrollments');

        // Restore backup data if exists
        $this->restoreBackupData();
    }

    private function backupExistingData()
    {
        // Create backup table
        $this->db->query("CREATE TABLE tb_unit_enrollments_backup AS SELECT * FROM tb_unit_enrollments");

        log_message('info', 'Unit enrollments data backed up to tb_unit_enrollments_backup');
    }

    private function createTemporaryTable()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Single unit ID instead of JSON array',
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
                'comment' => 'Amount paid for this specific unit',
            ],
            'payment_proof' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['instapay', 'vodafone_cash', 'free'],
                'default' => 'instapay',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tb_unit_enrollments_new');

        log_message('info', 'Temporary table tb_unit_enrollments_new created');
    }

    private function migrateData()
    {
        // Get all existing enrollments
        $query = $this->db->query("SELECT * FROM tb_unit_enrollments");
        $enrollments = $query->getResultArray();

        $migratedCount = 0;
        $errorCount = 0;

        foreach ($enrollments as $enrollment) {
            try {
                // Decode the JSON unit_ids
                $unitIds = json_decode($enrollment['unit_ids'], true);

                if (!is_array($unitIds) || empty($unitIds)) {
                    log_message('warning', "Invalid unit_ids for enrollment ID {$enrollment['id']}: {$enrollment['unit_ids']}");
                    $errorCount++;
                    continue;
                }

                // Calculate amount per unit
                $totalAmount = (float) $enrollment['total_amount'];
                $unitCount = count($unitIds);
                $amountPerUnit = $unitCount > 0 ? $totalAmount / $unitCount : 0;

                // Create individual enrollment records for each unit
                foreach ($unitIds as $unitId) {
                    $newEnrollmentData = [
                        'user_id' => $enrollment['user_id'],
                        'unit_id' => (int) $unitId,
                        'total_amount' => $amountPerUnit,
                        'payment_proof' => $enrollment['payment_proof'],
                        'payment_method' => $enrollment['payment_method'],
                        'status' => $enrollment['status'],
                        'admin_notes' => $enrollment['admin_notes'],
                        'processed_by' => $enrollment['processed_by'],
                        'processed_at' => $enrollment['processed_at'],
                        'created_at' => $enrollment['created_at'],
                        'updated_at' => $enrollment['updated_at'],
                    ];

                    $this->db->table('tb_unit_enrollments_new')->insert($newEnrollmentData);
                    $migratedCount++;
                }

            } catch (\Exception $e) {
                log_message('error', "Error migrating enrollment ID {$enrollment['id']}: " . $e->getMessage());
                $errorCount++;
            }
        }

        log_message('info', "Data migration completed. Migrated: {$migratedCount} records, Errors: {$errorCount}");
    }

    private function replaceTable()
    {
        // Drop the old table
        $this->forge->dropTable('tb_unit_enrollments', true);

        // Rename the new table
        $this->db->query("RENAME TABLE tb_unit_enrollments_new TO tb_unit_enrollments");

        log_message('info', 'Table replacement completed');
    }

    private function addIndexes()
    {
        // Add indexes for better performance
        $this->db->query("ALTER TABLE tb_unit_enrollments ADD INDEX idx_user_id (user_id)");
        $this->db->query("ALTER TABLE tb_unit_enrollments ADD INDEX idx_unit_id (unit_id)");
        $this->db->query("ALTER TABLE tb_unit_enrollments ADD INDEX idx_status (status)");
        $this->db->query("ALTER TABLE tb_unit_enrollments ADD INDEX idx_user_unit (user_id, unit_id)");

        log_message('info', 'Indexes added successfully');
    }

    private function restoreBackupData()
    {
        // Check if backup table exists
        if ($this->db->tableExists('tb_unit_enrollments_backup')) {
            $this->db->query("INSERT INTO tb_unit_enrollments SELECT * FROM tb_unit_enrollments_backup");
            log_message('info', 'Backup data restored');
        }
    }
}
