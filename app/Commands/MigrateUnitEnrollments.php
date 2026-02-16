<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MigrateUnitEnrollments extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'migrate:unit-enrollments';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Migrate unit enrollments from JSON array format to individual rows';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'migrate:unit-enrollments [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--dry-run' => 'Preview the migration without making changes',
        '--backup'  => 'Create backup before migration (default: true)',
        '--force'   => 'Force migration even if backup fails',
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run');
        $backup = CLI::getOption('backup') !== false; // Default to true
        $force = CLI::getOption('force');

        CLI::write('Unit Enrollments Migration Tool', 'yellow');
        CLI::write('=====================================', 'yellow');
        CLI::newLine();

        if ($dryRun) {
            CLI::write('DRY RUN MODE - No changes will be made', 'cyan');
            CLI::newLine();
        }

        // Initialize database
        $db = \Config\Database::connect();

        try {
            // Step 1: Check current data
            $this->analyzeCurrentData($db);

            // Step 2: Create backup if requested
            if ($backup && !$dryRun) {
                if (!$this->createBackup($db) && !$force) {
                    CLI::error('Backup failed. Use --force to continue without backup.');
                    return;
                }
            }

            // Step 3: Perform migration
            if (!$dryRun) {
                $this->performMigration($db);
            } else {
                $this->previewMigration($db);
            }

            CLI::write('Migration completed successfully!', 'green');

        } catch (\Exception $e) {
            CLI::error('Migration failed: ' . $e->getMessage());
            CLI::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    private function analyzeCurrentData($db)
    {
        CLI::write('Analyzing current enrollment data...', 'cyan');

        // Check if table exists
        if (!$db->tableExists('tb_unit_enrollments')) {
            throw new \Exception('Table tb_unit_enrollments does not exist');
        }

        // Get current data statistics
        $totalEnrollments = $db->table('tb_unit_enrollments')->countAll();
        CLI::write("Total enrollments found: {$totalEnrollments}");

        if ($totalEnrollments === 0) {
            CLI::write('No enrollments to migrate.', 'yellow');
            return;
        }

        // Analyze JSON data
        $query = $db->query("SELECT id, unit_ids FROM tb_unit_enrollments");
        $enrollments = $query->getResultArray();

        $totalUnits = 0;
        $invalidRecords = 0;

        foreach ($enrollments as $enrollment) {
            $unitIds = json_decode($enrollment['unit_ids'], true);
            
            if (!is_array($unitIds)) {
                $invalidRecords++;
                CLI::write("Invalid JSON in enrollment ID {$enrollment['id']}: {$enrollment['unit_ids']}", 'red');
            } else {
                $totalUnits += count($unitIds);
            }
        }

        CLI::write("Total individual unit enrollments to create: {$totalUnits}");
        CLI::write("Invalid records found: {$invalidRecords}");
        CLI::newLine();
    }

    private function createBackup($db)
    {
        CLI::write('Creating backup...', 'cyan');

        try {
            // Drop backup table if exists
            $db->query("DROP TABLE IF EXISTS tb_unit_enrollments_backup");
            
            // Create backup
            $db->query("CREATE TABLE tb_unit_enrollments_backup AS SELECT * FROM tb_unit_enrollments");
            
            $backupCount = $db->table('tb_unit_enrollments_backup')->countAll();
            CLI::write("Backup created successfully with {$backupCount} records", 'green');
            
            return true;
        } catch (\Exception $e) {
            CLI::error('Backup failed: ' . $e->getMessage());
            return false;
        }
    }

    private function previewMigration($db)
    {
        CLI::write('Migration Preview:', 'cyan');
        CLI::write('==================', 'cyan');

        $query = $db->query("SELECT * FROM tb_unit_enrollments LIMIT 3");
        $enrollments = $query->getResultArray();

        foreach ($enrollments as $enrollment) {
            CLI::write("Enrollment ID: {$enrollment['id']}", 'yellow');
            CLI::write("User ID: {$enrollment['user_id']}");
            CLI::write("Current unit_ids: {$enrollment['unit_ids']}");
            
            $unitIds = json_decode($enrollment['unit_ids'], true);
            if (is_array($unitIds)) {
                $totalAmount = (float) $enrollment['total_amount'];
                $amountPerUnit = count($unitIds) > 0 ? $totalAmount / count($unitIds) : 0;
                
                CLI::write("Will create " . count($unitIds) . " individual records:");
                foreach ($unitIds as $unitId) {
                    CLI::write("  - Unit ID: {$unitId}, Amount: {$amountPerUnit}");
                }
            } else {
                CLI::write("  ERROR: Invalid JSON format", 'red');
            }
            CLI::newLine();
        }
    }

    private function performMigration($db)
    {
        CLI::write('Starting migration...', 'cyan');

        $db->transStart();

        try {
            // Step 1: Create new table structure
            $this->createNewTableStructure($db);

            // Step 2: Migrate data
            $this->migrateData($db);

            // Step 3: Replace old table
            $this->replaceTable($db);

            // Step 4: Add indexes
            $this->addIndexes($db);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            CLI::write('Migration completed successfully!', 'green');

        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    private function createNewTableStructure($db)
    {
        CLI::write('Creating new table structure...', 'cyan');

        $sql = "
        CREATE TABLE tb_unit_enrollments_new (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id INT(11) UNSIGNED NOT NULL,
            unit_id INT(11) UNSIGNED NOT NULL COMMENT 'Single unit ID instead of JSON array',
            total_amount DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Amount paid for this specific unit',
            payment_proof JSON NULL,
            payment_method ENUM('instapay','vodafone_cash','free') DEFAULT 'instapay',
            status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
            admin_notes TEXT NULL,
            processed_by INT(11) UNSIGNED NULL,
            processed_at DATETIME NULL,
            original_enrollment_id INT(11) UNSIGNED NULL COMMENT 'Reference to original enrollment for grouping',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";

        $db->query($sql);
        CLI::write('New table structure created', 'green');
    }

    private function migrateData($db)
    {
        CLI::write('Migrating data...', 'cyan');

        $query = $db->query("SELECT * FROM tb_unit_enrollments");
        $enrollments = $query->getResultArray();

        $migratedCount = 0;
        $errorCount = 0;
        $totalEnrollments = count($enrollments);

        foreach ($enrollments as $index => $enrollment) {
            try {
                $unitIds = json_decode($enrollment['unit_ids'], true);
                
                if (!is_array($unitIds) || empty($unitIds)) {
                    CLI::write("Skipping enrollment ID {$enrollment['id']} - invalid unit_ids", 'yellow');
                    $errorCount++;
                    continue;
                }

                $totalAmount = (float) $enrollment['total_amount'];
                $unitCount = count($unitIds);
                $amountPerUnit = $unitCount > 0 ? $totalAmount / $unitCount : 0;

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
                        'original_enrollment_id' => $enrollment['id'],
                        'created_at' => $enrollment['created_at'],
                        'updated_at' => $enrollment['updated_at'],
                    ];

                    $db->table('tb_unit_enrollments_new')->insert($newEnrollmentData);
                    $migratedCount++;
                }

                // Progress indicator
                if (($index + 1) % 10 === 0 || ($index + 1) === $totalEnrollments) {
                    CLI::write("Progress: " . ($index + 1) . "/{$totalEnrollments} enrollments processed");
                }

            } catch (\Exception $e) {
                CLI::error("Error migrating enrollment ID {$enrollment['id']}: " . $e->getMessage());
                $errorCount++;
            }
        }

        CLI::write("Data migration completed. Migrated: {$migratedCount} records, Errors: {$errorCount}", 'green');
    }

    private function replaceTable($db)
    {
        CLI::write('Replacing old table...', 'cyan');

        $db->query("DROP TABLE tb_unit_enrollments");
        $db->query("RENAME TABLE tb_unit_enrollments_new TO tb_unit_enrollments");

        CLI::write('Table replacement completed', 'green');
    }

    private function addIndexes($db)
    {
        CLI::write('Adding indexes...', 'cyan');

        $indexes = [
            "ALTER TABLE tb_unit_enrollments ADD INDEX idx_user_id (user_id)",
            "ALTER TABLE tb_unit_enrollments ADD INDEX idx_unit_id (unit_id)",
            "ALTER TABLE tb_unit_enrollments ADD INDEX idx_status (status)",
            "ALTER TABLE tb_unit_enrollments ADD INDEX idx_user_unit (user_id, unit_id)",
            "ALTER TABLE tb_unit_enrollments ADD INDEX idx_original_enrollment (original_enrollment_id)"
        ];

        foreach ($indexes as $sql) {
            try {
                $db->query($sql);
            } catch (\Exception $e) {
                CLI::write("Warning: Could not create index - " . $e->getMessage(), 'yellow');
            }
        }

        CLI::write('Indexes added successfully', 'green');
    }
}