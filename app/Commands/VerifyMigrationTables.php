<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class VerifyMigrationTables extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'verify:migration';
    protected $description = 'Verify migration tables exist and check their structure';

    public function run(array $params)
    {
        $db = Database::connect();

        CLI::write('Checking migration tables...', 'yellow');
        CLI::newLine();

        // Check if tb_colleges exists
        if ($db->tableExists('tb_colleges')) {
            CLI::write('✓ tb_colleges table exists', 'green');
            $count = $db->table('tb_colleges')->countAllResults();
            CLI::write("  - Records: $count");
        } else {
            CLI::write('✗ tb_colleges table does NOT exist', 'red');
        }

        CLI::newLine();

        // Check if tb_departments exists
        if ($db->tableExists('tb_departments')) {
            CLI::write('✓ tb_departments table exists', 'green');
            $count = $db->table('tb_departments')->countAllResults();
            CLI::write("  - Records: $count");
            
            // Check structure
            $fields = $db->getFieldData('tb_departments');
            CLI::write("  - Columns: " . count($fields));
            foreach ($fields as $field) {
                CLI::write("    * {$field->name} ({$field->type})");
            }
            
            // Check indexes
            $indexes = $db->getIndexData('tb_departments');
            CLI::write("  - Indexes:");
            foreach ($indexes as $index) {
                $indexName = is_object($index) ? $index->name : $index['name'];
                CLI::write("    * {$indexName}");
            }
            
            // Check foreign keys
            CLI::write("  - Foreign Keys:");
            try {
                $foreignKeys = $db->getForeignKeyData('tb_departments');
                if (empty($foreignKeys)) {
                    CLI::write("    * (No foreign keys found)");
                } else {
                    CLI::write("    * Found " . count($foreignKeys) . " foreign key(s)");
                    foreach ($foreignKeys as $fk) {
                        CLI::write("    * " . json_encode($fk, JSON_PRETTY_PRINT));
                    }
                }
            } catch (\Exception $e) {
                CLI::write("    * Error retrieving foreign keys: " . $e->getMessage());
            }
        } else {
            CLI::write('✗ tb_departments table does NOT exist', 'red');
        }

        CLI::newLine();

        // Check if tb_courses has new columns
        if ($db->tableExists('tb_courses')) {
            CLI::write('✓ tb_courses table exists', 'green');
            $hasCollegeId = $db->fieldExists('college_id', 'tb_courses');
            $hasDepartmentId = $db->fieldExists('department_id', 'tb_courses');
            
            CLI::write("  - college_id column: " . ($hasCollegeId ? "EXISTS" : "MISSING"));
            CLI::write("  - department_id column: " . ($hasDepartmentId ? "EXISTS" : "MISSING"));
        }

        CLI::newLine();
    }
}
