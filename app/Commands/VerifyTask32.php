<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class VerifyTask32 extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'verify:task32';
    protected $description = 'Verify Task 3.2 requirements are met (insertDepartmentData method)';

    public function run(array $params)
    {
        $db = Database::connect();
        $passed = true;

        CLI::write('=================================', 'yellow');
        CLI::write('Task 3.2 Verification', 'yellow');
        CLI::write('=================================', 'yellow');
        CLI::newLine();

        // Requirement: Check if tb_departments table exists first
        CLI::write('0. Checking if tb_departments table exists...', 'cyan');
        if (!$db->tableExists('tb_departments')) {
            CLI::write('   ✗ FAIL: tb_departments table does not exist. Run migration first.', 'red');
            CLI::newLine();
            return;
        }
        CLI::write('   ✓ PASS: tb_departments table exists', 'green');
        CLI::newLine();

        // Requirement: Check if at least 12 departments exist
        CLI::write('1. Checking if at least 12 departments are inserted...', 'cyan');
        $count = $db->table('tb_departments')->countAllResults();
        if ($count >= 12) {
            CLI::write("   ✓ PASS: Found {$count} departments (minimum 12 required)", 'green');
        } else {
            CLI::write("   ✗ FAIL: Only {$count} departments found (minimum 12 required)", 'red');
            $passed = false;
        }
        CLI::newLine();

        // Requirement: Verify specific departments from the spec
        CLI::write('2. Verifying required departments exist...', 'cyan');
        
        $requiredDepartments = [
            // Computing College (college_id=1)
            ['college_id' => 1, 'code' => 'DEPT001', 'name_ar' => 'قسم علوم الحاسب', 'name_en' => 'Computer Science Department'],
            ['college_id' => 1, 'code' => 'DEPT002', 'name_ar' => 'قسم تقنية المعلومات', 'name_en' => 'Information Technology Department'],
            ['college_id' => 1, 'code' => 'DEPT003', 'name_ar' => 'قسم أمن المعلومات', 'name_en' => 'Information Security Department'],
            ['college_id' => 1, 'code' => 'DEPT004', 'name_ar' => 'قسم علوم البيانات والذكاء الاصطناعي', 'name_en' => 'Data Science and Artificial Intelligence Department'],
            // Administrative College (college_id=2)
            ['college_id' => 2, 'code' => 'DEPT005', 'name_ar' => 'قسم إدارة الأعمال', 'name_en' => 'Business Administration Department'],
            ['college_id' => 2, 'code' => 'DEPT006', 'name_ar' => 'قسم المحاسبة', 'name_en' => 'Accounting Department'],
            ['college_id' => 2, 'code' => 'DEPT007', 'name_ar' => 'قسم المالية', 'name_en' => 'Finance Department'],
            ['college_id' => 2, 'code' => 'DEPT008', 'name_ar' => 'قسم التجارة الإلكترونية', 'name_en' => 'E-Commerce Department'],
            // Health College (college_id=3)
            ['college_id' => 3, 'code' => 'DEPT009', 'name_ar' => 'قسم الصحة العامة', 'name_en' => 'Public Health Department'],
            ['college_id' => 3, 'code' => 'DEPT010', 'name_ar' => 'قسم المعلوماتية الصحية', 'name_en' => 'Health Informatics Department'],
            // Basic Sciences College (college_id=4)
            ['college_id' => 4, 'code' => 'DEPT011', 'name_ar' => 'قسم الرياضيات', 'name_en' => 'Mathematics Department'],
            ['college_id' => 4, 'code' => 'DEPT012', 'name_ar' => 'قسم اللغة الإنجليزية', 'name_en' => 'English Language Department'],
        ];

        foreach ($requiredDepartments as $dept) {
            $result = $db->table('tb_departments')
                ->where('department_code', $dept['code'])
                ->get()
                ->getRow();
            
            if ($result) {
                // Check all fields
                $errors = [];
                if ($result->college_id != $dept['college_id']) {
                    $errors[] = "college_id mismatch (expected {$dept['college_id']}, got {$result->college_id})";
                }
                if ($result->department_name_ar !== $dept['name_ar']) {
                    $errors[] = "Arabic name mismatch";
                }
                if ($result->department_name_en !== $dept['name_en']) {
                    $errors[] = "English name mismatch";
                }
                if ($result->active != 1) {
                    $errors[] = "active should be 1";
                }
                
                if (empty($errors)) {
                    CLI::write("   ✓ PASS: {$dept['code']} - {$dept['name_ar']}", 'green');
                } else {
                    CLI::write("   ✗ FAIL: {$dept['code']} - " . implode(', ', $errors), 'red');
                    $passed = false;
                }
            } else {
                CLI::write("   ✗ FAIL: {$dept['code']} not found", 'red');
                $passed = false;
            }
        }
        CLI::newLine();

        // Requirement: Verify all departments have required fields populated
        CLI::write('3. Checking all departments have required fields...', 'cyan');
        $departments = $db->table('tb_departments')->get()->getResult();
        $allValid = true;
        
        foreach ($departments as $dept) {
            $issues = [];
            if (empty($dept->college_id)) $issues[] = 'college_id is empty';
            if (empty($dept->department_name_ar)) $issues[] = 'department_name_ar is empty';
            if (empty($dept->department_code)) $issues[] = 'department_code is empty';
            if (is_null($dept->created_at)) $issues[] = 'created_at is null';
            if (is_null($dept->updated_at)) $issues[] = 'updated_at is null';
            
            if (!empty($issues)) {
                CLI::write("   ✗ FAIL: Department {$dept->department_code} has issues: " . implode(', ', $issues), 'red');
                $allValid = false;
                $passed = false;
            }
        }
        
        if ($allValid) {
            CLI::write('   ✓ PASS: All departments have required fields populated', 'green');
        }
        CLI::newLine();

        // Requirement: Verify department code format (DEPT001, DEPT002, etc.)
        CLI::write('4. Checking department code format...', 'cyan');
        $codeFormatValid = true;
        foreach ($departments as $dept) {
            if (!preg_match('/^DEPT\d{3}$/', $dept->department_code)) {
                CLI::write("   ✗ FAIL: {$dept->department_code} does not match format DEPTXXX", 'red');
                $codeFormatValid = false;
                $passed = false;
            }
        }
        
        if ($codeFormatValid) {
            CLI::write('   ✓ PASS: All department codes follow DEPTXXX format', 'green');
        }
        CLI::newLine();

        // Requirement: Verify distribution across colleges
        CLI::write('5. Checking department distribution across colleges...', 'cyan');
        $collegeDistribution = [
            1 => ['name' => 'Computing', 'expected' => 4, 'actual' => 0],
            2 => ['name' => 'Administrative', 'expected' => 4, 'actual' => 0],
            3 => ['name' => 'Health', 'expected' => 2, 'actual' => 0],
            4 => ['name' => 'Basic Sciences', 'expected' => 2, 'actual' => 0],
        ];
        
        foreach ($departments as $dept) {
            if (isset($collegeDistribution[$dept->college_id])) {
                $collegeDistribution[$dept->college_id]['actual']++;
            }
        }
        
        foreach ($collegeDistribution as $collegeId => $data) {
            if ($data['actual'] >= $data['expected']) {
                CLI::write("   ✓ PASS: College {$collegeId} ({$data['name']}) has {$data['actual']} departments (expected {$data['expected']})", 'green');
            } else {
                CLI::write("   ✗ FAIL: College {$collegeId} ({$data['name']}) has only {$data['actual']} departments (expected {$data['expected']})", 'red');
                $passed = false;
            }
        }
        CLI::newLine();

        // Requirement: Check that insertBatch was used (verify data consistency)
        CLI::write('6. Checking data consistency (batch insert verification)...', 'cyan');
        $timestamps = array_unique(array_map(function($dept) {
            return substr($dept->created_at, 0, 10); // Get date part only
        }, $departments));
        
        if (count($timestamps) == 1) {
            CLI::write('   ✓ PASS: All departments have same date (consistent batch insert)', 'green');
        } else {
            CLI::write('   ⚠ WARNING: Departments have different dates (may indicate multiple inserts)', 'yellow');
        }
        CLI::newLine();

        // Summary
        CLI::write('=================================', 'yellow');
        if ($passed) {
            CLI::write('✓ ALL CHECKS PASSED', 'green');
            CLI::write('Task 3.2 requirements are fully met!', 'green');
            CLI::write('insertDepartmentData() method is correctly implemented.', 'green');
        } else {
            CLI::write('✗ SOME CHECKS FAILED', 'red');
            CLI::write('Task 3.2 has issues that need to be addressed.', 'red');
        }
        CLI::write('=================================', 'yellow');
        CLI::newLine();
    }
}
