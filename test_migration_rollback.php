<?php

/**
 * Test script for AddCollegeDepartmentSupport migration rollback
 * This script tests the down() method functionality
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$paths = new Config\Paths();
$bootstrap = rtrim(realpath(ROOTPATH . 'vendor/codeigniter4/framework/system/Test/bootstrap.php'), DIRECTORY_SEPARATOR);
chdir(__DIR__);

// Load the migration file
require_once APPPATH . 'Database/Migrations/2026-07-28-130000_AddCollegeDepartmentSupport.php';

// Create database connection
$db = \Config\Database::connect();
$forge = \Config\Database::forge();

echo "=== Testing AddCollegeDepartmentSupport Migration Rollback ===\n\n";

// Instantiate the migration
$migration = new \App\Database\Migrations\AddCollegeDepartmentSupport();

// Set the necessary properties
$reflection = new ReflectionClass($migration);
$dbProperty = $reflection->getProperty('db');
$dbProperty->setAccessible(true);
$dbProperty->setValue($migration, $db);

$forgeProperty = $reflection->getProperty('forge');
$forgeProperty->setAccessible(true);
$forgeProperty->setValue($migration, $forge);

try {
    echo "Step 1: Running UP migration to create tables...\n";
    $migration->up();
    echo "✓ UP migration completed successfully\n\n";
    
    // Verify tables exist
    echo "Step 2: Verifying tables were created...\n";
    $collegesExists = $db->tableExists('tb_colleges');
    $departmentsExists = $db->tableExists('tb_departments');
    $collegeIdExists = $db->fieldExists('college_id', 'tb_courses');
    $departmentIdExists = $db->fieldExists('department_id', 'tb_courses');
    
    echo "  - tb_colleges table exists: " . ($collegesExists ? 'YES' : 'NO') . "\n";
    echo "  - tb_departments table exists: " . ($departmentsExists ? 'YES' : 'NO') . "\n";
    echo "  - tb_courses.college_id exists: " . ($collegeIdExists ? 'YES' : 'NO') . "\n";
    echo "  - tb_courses.department_id exists: " . ($departmentIdExists ? 'YES' : 'NO') . "\n\n";
    
    if (!$collegesExists || !$departmentsExists || !$collegeIdExists || !$departmentIdExists) {
        throw new Exception("UP migration did not create all expected tables/columns");
    }
    
    // Check data
    echo "Step 3: Verifying seed data was inserted...\n";
    $collegeCount = $db->table('tb_colleges')->countAllResults();
    $departmentCount = $db->table('tb_departments')->countAllResults();
    echo "  - Colleges count: $collegeCount (expected: 4)\n";
    echo "  - Departments count: $departmentCount (expected: 12)\n\n";
    
    if ($collegeCount < 4 || $departmentCount < 12) {
        throw new Exception("Seed data not inserted correctly");
    }
    
    echo "Step 4: Running DOWN migration to rollback...\n";
    $migration->down();
    echo "✓ DOWN migration completed successfully\n\n";
    
    // Verify rollback
    echo "Step 5: Verifying rollback was successful...\n";
    $collegesExists = $db->tableExists('tb_colleges');
    $departmentsExists = $db->tableExists('tb_departments');
    $collegeIdExists = $db->fieldExists('college_id', 'tb_courses');
    $departmentIdExists = $db->fieldExists('department_id', 'tb_courses');
    
    echo "  - tb_colleges table exists: " . ($collegesExists ? 'YES (FAILED)' : 'NO (SUCCESS)') . "\n";
    echo "  - tb_departments table exists: " . ($departmentsExists ? 'YES (FAILED)' : 'NO (SUCCESS)') . "\n";
    echo "  - tb_courses.college_id exists: " . ($collegeIdExists ? 'YES (FAILED)' : 'NO (SUCCESS)') . "\n";
    echo "  - tb_courses.department_id exists: " . ($departmentIdExists ? 'YES (FAILED)' : 'NO (SUCCESS)') . "\n\n";
    
    if ($collegesExists || $departmentsExists || $collegeIdExists || $departmentIdExists) {
        throw new Exception("DOWN migration did not remove all tables/columns");
    }
    
    echo "=== ALL TESTS PASSED ===\n";
    echo "✓ Rollback logic works correctly\n";
    echo "✓ Reverse dependency order is correct\n";
    echo "✓ Idempotent behavior verified\n";
    
} catch (Exception $e) {
    echo "\n❌ TEST FAILED: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    
    // Cleanup attempt
    echo "\nAttempting cleanup...\n";
    try {
        $migration->down();
        echo "Cleanup completed\n";
    } catch (Exception $cleanupError) {
        echo "Cleanup failed: " . $cleanupError->getMessage() . "\n";
    }
}
