<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class VerifyDepartments extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'verify:departments';
    protected $description = 'Verify department data in tb_departments';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Verifying department data...', 'yellow');
        CLI::newLine();
        
        $departments = $db->table('tb_departments')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResult();
        
        CLI::write('Total Departments: ' . count($departments), 'green');
        CLI::newLine();
        
        foreach ($departments as $dept) {
            CLI::write("ID: {$dept->id} | College ID: {$dept->college_id}", 'cyan');
            CLI::write("  Arabic: {$dept->department_name_ar}", 'white');
            CLI::write("  English: {$dept->department_name_en}", 'white');
            CLI::write("  Code: {$dept->department_code} | Active: {$dept->active}", 'white');
            CLI::newLine();
        }
        
        // Verify expected data
        $expectedDepartments = [
            ['college_id' => 1, 'code' => 'DEPT001', 'name_ar' => 'قسم علوم الحاسب'],
            ['college_id' => 1, 'code' => 'DEPT002', 'name_ar' => 'قسم تقنية المعلومات'],
            ['college_id' => 1, 'code' => 'DEPT003', 'name_ar' => 'قسم أمن المعلومات'],
            ['college_id' => 1, 'code' => 'DEPT004', 'name_ar' => 'قسم علوم البيانات والذكاء الاصطناعي'],
            ['college_id' => 2, 'code' => 'DEPT005', 'name_ar' => 'قسم إدارة الأعمال'],
            ['college_id' => 2, 'code' => 'DEPT006', 'name_ar' => 'قسم المحاسبة'],
            ['college_id' => 2, 'code' => 'DEPT007', 'name_ar' => 'قسم المالية'],
            ['college_id' => 2, 'code' => 'DEPT008', 'name_ar' => 'قسم التجارة الإلكترونية'],
            ['college_id' => 3, 'code' => 'DEPT009', 'name_ar' => 'قسم الصحة العامة'],
            ['college_id' => 3, 'code' => 'DEPT010', 'name_ar' => 'قسم المعلوماتية الصحية'],
            ['college_id' => 4, 'code' => 'DEPT011', 'name_ar' => 'قسم الرياضيات'],
            ['college_id' => 4, 'code' => 'DEPT012', 'name_ar' => 'قسم اللغة الإنجليزية'],
        ];
        
        CLI::write('Verification:', 'yellow');
        $allMatch = true;
        foreach ($expectedDepartments as $index => $expected) {
            $actual = $departments[$index] ?? null;
            if ($actual && 
                $actual->college_id == $expected['college_id'] && 
                $actual->department_code == $expected['code'] &&
                $actual->department_name_ar == $expected['name_ar']) {
                CLI::write("✓ {$expected['code']}: Correct", 'green');
            } else {
                CLI::write("✗ {$expected['code']}: Mismatch", 'red');
                $allMatch = false;
            }
        }
        
        CLI::newLine();
        if ($allMatch && count($departments) === 12) {
            CLI::write('✓ All 12 departments verified successfully!', 'green');
        } else {
            CLI::write('✗ Verification failed!', 'red');
        }
    }
}
