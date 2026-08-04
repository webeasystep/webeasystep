<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class VerifyTask31 extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'verify:task31';
    protected $description = 'Verify Task 3.1 requirements are met';

    public function run(array $params)
    {
        $db = Database::connect();
        $passed = true;

        CLI::write('=================================', 'yellow');
        CLI::write('Task 3.1 Verification', 'yellow');
        CLI::write('=================================', 'yellow');
        CLI::newLine();

        // Requirement: Create tb_departments table
        CLI::write('1. Checking if tb_departments table exists...', 'cyan');
        if ($db->tableExists('tb_departments')) {
            CLI::write('   ✓ PASS: tb_departments table exists', 'green');
        } else {
            CLI::write('   ✗ FAIL: tb_departments table does not exist', 'red');
            $passed = false;
        }
        CLI::newLine();

        // Requirement: Table structure
        CLI::write('2. Checking table structure...', 'cyan');
        $fields = $db->getFieldData('tb_departments');
        $fieldNames = array_column($fields, 'name');
        
        $requiredFields = [
            'id' => 'int',
            'college_id' => 'int',
            'department_name_ar' => 'varchar',
            'department_name_en' => 'varchar',
            'department_code' => 'varchar',
            'active' => 'tinyint',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];

        foreach ($requiredFields as $fieldName => $fieldType) {
            if (in_array($fieldName, $fieldNames)) {
                // Find the field and check type
                $field = array_filter($fields, fn($f) => $f->name === $fieldName);
                $field = reset($field);
                if (strpos(strtolower($field->type), $fieldType) !== false) {
                    CLI::write("   ✓ PASS: Column '$fieldName' exists with type '$field->type'", 'green');
                } else {
                    CLI::write("   ✗ FAIL: Column '$fieldName' has wrong type '$field->type' (expected $fieldType)", 'red');
                    $passed = false;
                }
            } else {
                CLI::write("   ✗ FAIL: Required column '$fieldName' is missing", 'red');
                $passed = false;
            }
        }
        CLI::newLine();

        // Requirement: Primary key on id
        CLI::write('3. Checking primary key...', 'cyan');
        $indexes = $db->getIndexData('tb_departments');
        $hasPrimaryKey = false;
        foreach ($indexes as $index) {
            $indexName = is_object($index) ? $index->name : $index['name'];
            if ($indexName === 'PRIMARY') {
                $hasPrimaryKey = true;
                CLI::write('   ✓ PASS: Primary key exists on id column', 'green');
                break;
            }
        }
        if (!$hasPrimaryKey) {
            CLI::write('   ✗ FAIL: No primary key found', 'red');
            $passed = false;
        }
        CLI::newLine();

        // Requirement: Unique index on department_code
        CLI::write('4. Checking unique index on department_code...', 'cyan');
        $hasUniqueIndex = false;
        foreach ($indexes as $index) {
            $indexName = is_object($index) ? $index->name : $index['name'];
            if ($indexName === 'department_code') {
                $hasUniqueIndex = true;
                CLI::write('   ✓ PASS: Unique index on department_code exists', 'green');
                break;
            }
        }
        if (!$hasUniqueIndex) {
            CLI::write('   ✗ FAIL: Unique index on department_code not found', 'red');
            $passed = false;
        }
        CLI::newLine();

        // Requirement: Index on college_id
        CLI::write('5. Checking index on college_id...', 'cyan');
        $hasCollegeIdIndex = false;
        foreach ($indexes as $index) {
            $indexName = is_object($index) ? $index->name : $index['name'];
            if ($indexName === 'college_id') {
                $hasCollegeIdIndex = true;
                CLI::write('   ✓ PASS: Index on college_id exists', 'green');
                break;
            }
        }
        if (!$hasCollegeIdIndex) {
            CLI::write('   ✗ FAIL: Index on college_id not found', 'red');
            $passed = false;
        }
        CLI::newLine();

        // Requirement: Foreign key on college_id
        CLI::write('6. Checking foreign key constraint on college_id...', 'cyan');
        try {
            $foreignKeys = $db->getForeignKeyData('tb_departments');
            $hasForeignKey = false;
            $correctCascade = false;
            
            foreach ($foreignKeys as $fk) {
                if (in_array('college_id', $fk->column_name)) {
                    $hasForeignKey = true;
                    if ($fk->foreign_table_name === 'tb_colleges' && 
                        in_array('id', $fk->foreign_column_name)) {
                        CLI::write('   ✓ PASS: Foreign key references tb_colleges.id', 'green');
                        
                        if ($fk->on_delete === 'CASCADE' && $fk->on_update === 'CASCADE') {
                            CLI::write('   ✓ PASS: Foreign key has CASCADE on DELETE and UPDATE', 'green');
                            $correctCascade = true;
                        } else {
                            CLI::write("   ✗ FAIL: Foreign key has incorrect CASCADE rules (ON DELETE: {$fk->on_delete}, ON UPDATE: {$fk->on_update})", 'red');
                            $passed = false;
                        }
                    }
                }
            }
            
            if (!$hasForeignKey) {
                CLI::write('   ✗ FAIL: No foreign key constraint found on college_id', 'red');
                $passed = false;
            }
        } catch (\Exception $e) {
            CLI::write('   ✗ FAIL: Error checking foreign key: ' . $e->getMessage(), 'red');
            $passed = false;
        }
        CLI::newLine();

        // Requirement: Check character encoding
        CLI::write('7. Checking character encoding (utf8mb4)...', 'cyan');
        $query = "SELECT TABLE_COLLATION 
                  FROM information_schema.TABLES 
                  WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = 'tb_departments'";
        $result = $db->query($query)->getRow();
        
        if ($result && strpos($result->TABLE_COLLATION, 'utf8mb4') !== false) {
            CLI::write("   ✓ PASS: Table uses utf8mb4 collation ({$result->TABLE_COLLATION})", 'green');
        } else {
            CLI::write('   ✗ FAIL: Table does not use utf8mb4 collation', 'red');
            $passed = false;
        }
        CLI::newLine();

        // Summary
        CLI::write('=================================', 'yellow');
        if ($passed) {
            CLI::write('✓ ALL CHECKS PASSED', 'green');
            CLI::write('Task 3.1 requirements are fully met!', 'green');
        } else {
            CLI::write('✗ SOME CHECKS FAILED', 'red');
            CLI::write('Task 3.1 has issues that need to be addressed.', 'red');
        }
        CLI::write('=================================', 'yellow');
        CLI::newLine();
    }
}
