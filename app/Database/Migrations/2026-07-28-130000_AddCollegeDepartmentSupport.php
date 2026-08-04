<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Add College and Department Support for SEU
 * 
 * Creates organizational hierarchy tables (colleges and departments) and links them to courses.
 * Implements Saudi Electronic University's organizational structure with support for Arabic/English names.
 * 
 * Validates: Requirements 5.1, 5.2, 5.3
 */
class AddCollegeDepartmentSupport extends Migration
{
    /**
     * Execute the migration
     * 
     * Creates tables in dependency order:
     * 1. tb_colleges (no dependencies)
     * 2. tb_departments (depends on colleges)
     * 3. Add columns to tb_courses (depends on colleges and departments)
     * 
     * Validates: Requirements 5.4, 1.4, 2.5
     */
    public function up()
    {
        try {
            // Step 1: Check if tb_colleges table exists, create if not
            if (!$this->db->tableExists('tb_colleges')) {
                $this->createCollegesTable();
            }

            // Step 2: Insert college seed data (only if table was just created or is empty)
            $collegeCount = $this->db->table('tb_colleges')->countAllResults();
            if ($collegeCount === 0) {
                $this->insertCollegeData();
            }

            // Step 3: Check if tb_departments table exists, create if not
            if (!$this->db->tableExists('tb_departments')) {
                $this->createDepartmentsTable();
            }

            // Step 4: Insert department seed data (only if table was just created or is empty)
            $departmentCount = $this->db->table('tb_departments')->countAllResults();
            if ($departmentCount === 0) {
                $this->insertDepartmentData();
            }

            // Step 5: Check if college_id and department_id columns exist on tb_courses, add if not
            $hasCollegeId = $this->db->fieldExists('college_id', 'tb_courses');
            $hasDepartmentId = $this->db->fieldExists('department_id', 'tb_courses');
            
            if (!$hasCollegeId || !$hasDepartmentId) {
                $this->addCoursesTableColumns();
            }

        } catch (\Exception $e) {
            // Log the error and re-throw for proper migration failure handling
            log_message('error', 'Migration AddCollegeDepartmentSupport failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Rollback the migration
     * 
     * Drops tables in reverse dependency order:
     * 1. Drop columns from tb_courses
     * 2. Drop tb_departments
     * 3. Drop tb_colleges
     * 
     * Validates: Requirements 5.5, 1.4, 2.5, 3.7
     */
    public function down()
    {
        try {
            // Step 1: Remove foreign keys and columns from tb_courses table
            // Must be done first due to foreign key dependencies
            if ($this->db->tableExists('tb_courses')) {
                // Drop foreign key constraints first before dropping columns
                if ($this->db->fieldExists('college_id', 'tb_courses')) {
                    // Drop foreign key constraint for college_id
                    $this->forge->dropForeignKey('tb_courses', 'tb_courses_college_id_foreign');
                }
                
                if ($this->db->fieldExists('department_id', 'tb_courses')) {
                    // Drop foreign key constraint for department_id
                    $this->forge->dropForeignKey('tb_courses', 'tb_courses_department_id_foreign');
                }
                
                // Now drop the columns
                if ($this->db->fieldExists('college_id', 'tb_courses')) {
                    $this->forge->dropColumn('tb_courses', 'college_id');
                }
                
                if ($this->db->fieldExists('department_id', 'tb_courses')) {
                    $this->forge->dropColumn('tb_courses', 'department_id');
                }
            }

            // Step 2: Drop tb_departments table
            // Must be done before dropping tb_colleges due to foreign key dependency
            if ($this->db->tableExists('tb_departments')) {
                $this->forge->dropTable('tb_departments', true);
            }

            // Step 3: Drop tb_colleges table
            // Must be done last as other tables reference it
            if ($this->db->tableExists('tb_colleges')) {
                $this->forge->dropTable('tb_colleges', true);
            }

        } catch (\Exception $e) {
            // Log the error and re-throw for proper migration failure handling
            log_message('error', 'Migration rollback AddCollegeDepartmentSupport failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create the colleges table
     * 
     * Creates tb_colleges with Arabic/English names, unique college codes,
     * and UTF-8mb4 encoding for proper Arabic text support.
     * 
     * Validates: Requirements 1.1, 1.2, 7.1, 7.3, 8.1
     */
    private function createCollegesTable(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'college_name_ar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'college_name_en' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'college_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('college_code');
        
        $this->forge->createTable('tb_colleges', true, [
            'ENGINE' => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    /**
     * Create the departments table
     * 
     * Creates tb_departments with Arabic/English names, unique department codes,
     * foreign key to colleges, and UTF-8mb4 encoding for proper Arabic text support.
     * 
     * Validates: Requirements 2.1, 2.2, 2.3, 7.2, 7.3, 8.2
     */
    private function createDepartmentsTable(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'college_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'department_name_ar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'department_name_en' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'department_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('college_id');
        $this->forge->addUniqueKey('department_code');
        $this->forge->addForeignKey('college_id', 'tb_colleges', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('tb_departments', true, [
            'ENGINE' => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    /**
     * Insert SEU college seed data
     * 
     * Populates tb_colleges with 4 Saudi Electronic University colleges
     * with accurate Arabic and English names.
     * 
     * Validates: Requirements 1.3, 6.1, 6.3, 8.1
     */
    private function insertCollegeData(): void
    {
        $colleges = [
            [
                'college_name_ar' => 'كلية الحوسبة والمعلوماتية',
                'college_name_en' => 'College of Computing and Informatics',
                'college_code' => 'COL001',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_name_ar' => 'كلية العلوم الإدارية والمالية',
                'college_name_en' => 'College of Administrative and Financial Sciences',
                'college_code' => 'COL002',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_name_ar' => 'كلية الصحة والحياة',
                'college_name_en' => 'College of Health and Life Sciences',
                'college_code' => 'COL003',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_name_ar' => 'كلية العلوم الأساسية والنظرية',
                'college_name_en' => 'College of Basic and Theoretical Sciences',
                'college_code' => 'COL004',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_name_ar' => 'السنة الأولى المشتركة',
                'college_name_en' => 'Common First Year',
                'college_code' => 'COL005',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tb_colleges')->insertBatch($colleges);
    }

    /**
     * Insert SEU department seed data
     * 
     * Populates tb_departments with at least 12 departments mapped to colleges.
     * Includes Computer Science, IT, and Information Security departments under
     * College of Computing and Informatics.
     * 
     * Validates: Requirements 2.4, 6.2, 6.4, 6.5, 8.2
     */
    private function insertDepartmentData(): void
    {
        $departments = [
            // College of Computing and Informatics (COL001, college_id = 1)
            [
                'college_id' => 1,
                'department_name_ar' => 'قسم علوم الحاسب',
                'department_name_en' => 'Computer Science Department',
                'department_code' => 'DEPT001',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 1,
                'department_name_ar' => 'قسم تقنية المعلومات',
                'department_name_en' => 'Information Technology Department',
                'department_code' => 'DEPT002',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 1,
                'department_name_ar' => 'قسم أمن المعلومات',
                'department_name_en' => 'Information Security Department',
                'department_code' => 'DEPT003',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 1,
                'department_name_ar' => 'قسم علوم البيانات والذكاء الاصطناعي',
                'department_name_en' => 'Data Science and Artificial Intelligence Department',
                'department_code' => 'DEPT004',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // College of Administrative and Financial Sciences (COL002, college_id = 2)
            [
                'college_id' => 2,
                'department_name_ar' => 'قسم إدارة الأعمال',
                'department_name_en' => 'Business Administration Department',
                'department_code' => 'DEPT005',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 2,
                'department_name_ar' => 'قسم المحاسبة',
                'department_name_en' => 'Accounting Department',
                'department_code' => 'DEPT006',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 2,
                'department_name_ar' => 'قسم المالية',
                'department_name_en' => 'Finance Department',
                'department_code' => 'DEPT007',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 2,
                'department_name_ar' => 'قسم التجارة الإلكترونية',
                'department_name_en' => 'E-Commerce Department',
                'department_code' => 'DEPT008',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // College of Health and Life Sciences (COL003, college_id = 3)
            [
                'college_id' => 3,
                'department_name_ar' => 'قسم الصحة العامة',
                'department_name_en' => 'Public Health Department',
                'department_code' => 'DEPT009',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 3,
                'department_name_ar' => 'قسم المعلوماتية الصحية',
                'department_name_en' => 'Health Informatics Department',
                'department_code' => 'DEPT010',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // College of Basic and Theoretical Sciences (COL004, college_id = 4)
            [
                'college_id' => 4,
                'department_name_ar' => 'قسم الرياضيات',
                'department_name_en' => 'Mathematics Department',
                'department_code' => 'DEPT011',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'college_id' => 4,
                'department_name_ar' => 'قسم اللغة الإنجليزية',
                'department_name_en' => 'English Language Department',
                'department_code' => 'DEPT012',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Common First Year (COL005, college_id = 5)
            [
                'college_id' => 5,
                'department_name_ar' => 'قسم السنة الأولى المشتركة',
                'department_name_en' => 'Common First Year Department',
                'department_code' => 'DEPT013',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tb_departments')->insertBatch($departments);
    }

    /**
     * Add college_id and department_id columns to tb_courses table
     * 
     * Enhances the courses table with foreign key relationships to colleges and departments.
     * Uses nullable columns to allow gradual migration of existing course data.
     * Uses RESTRICT on delete to prevent deletion of colleges/departments with associated courses.
     * 
     * Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 3.6
     */
    private function addCoursesTableColumns(): void
    {
        // Add college_id column
        $this->forge->addColumn('tb_courses', [
            'college_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id',
            ],
        ]);

        // Add department_id column
        $this->forge->addColumn('tb_courses', [
            'department_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'college_id',
            ],
        ]);

        // Create index on college_id
        $this->forge->addKey('college_id');
        $this->forge->processIndexes('tb_courses');

        // Create index on department_id
        $this->forge->addKey('department_id');
        $this->forge->processIndexes('tb_courses');

        // Add foreign key constraint from college_id to tb_colleges.id
        // RESTRICT on delete prevents deletion of colleges with associated courses
        // CASCADE on update maintains referential integrity when IDs change
        $this->forge->addForeignKey('college_id', 'tb_colleges', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->processIndexes('tb_courses');

        // Add foreign key constraint from department_id to tb_departments.id
        // RESTRICT on delete prevents deletion of departments with associated courses
        // CASCADE on update maintains referential integrity when IDs change
        $this->forge->addForeignKey('department_id', 'tb_departments', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->processIndexes('tb_courses');
    }
}
