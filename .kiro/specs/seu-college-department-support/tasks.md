# Implementation Plan: SEU College-Department Support

## Overview

This implementation adds organizational hierarchy support for Saudi Electronic University (SEU) by creating a database migration that establishes colleges and departments tables, links them to the existing courses table, and populates the system with SEU's actual organizational structure. The migration follows CodeIgniter 4 conventions and ensures proper foreign key constraints, UTF-8mb4 encoding for Arabic text, and data integrity rules.

## Tasks

- [x] 1. Create migration file structure
  - [x] 1.1 Create migration file with correct naming and timestamp
    - Create file: `app/Database/Migrations/2026-07-28-130000_AddCollegeDepartmentSupport.php`
    - Define class `AddCollegeDepartmentSupport` extending `CodeIgniter\Database\Migration`
    - Implement skeleton `up()` and `down()` methods with private helper method declarations
    - _Requirements: 5.1, 5.2, 5.3_

- [ ] 2. Implement colleges table creation
  - [x] 2.1 Implement `createCollegesTable()` private method
    - Create `tb_colleges` table with columns: id (INT, auto-increment, primary key), college_name_ar (VARCHAR 255, NOT NULL), college_name_en (VARCHAR 255, NULL), college_code (VARCHAR 50, UNIQUE, NOT NULL), active (TINYINT, default 1), created_at (DATETIME), updated_at (DATETIME)
    - Set table charset to utf8mb4 and collation to utf8mb4_unicode_ci
    - Add unique index on `college_code` column
    - Use CodeIgniter 4 Forge class methods: `addField()`, `addKey()`, `addUniqueKey()`, `createTable()`
    - _Requirements: 1.1, 1.2, 7.1, 7.3, 8.1_
  
  - [-] 2.2 Implement `insertCollegeData()` private method
    - Insert 4 SEU colleges using batch insert
    - Colleges: كلية الحوسبة والمعلوماتية (COL001), كلية العلوم الإدارية والمالية (COL002), كلية الصحة والحياة (COL003), كلية العلوم الأساسية والنظرية (COL004)
    - Each record includes: college_name_ar, college_name_en, college_code, active=1, created_at, updated_at
    - Use `$this->db->table('tb_colleges')->insertBatch($colleges)`
    - _Requirements: 1.3, 6.1, 6.3, 8.1_

- [ ] 3. Implement departments table creation
  - [x] 3.1 Implement `createDepartmentsTable()` private method
    - Create `tb_departments` table with columns: id (INT, auto-increment, primary key), college_id (INT, NOT NULL), department_name_ar (VARCHAR 255, NOT NULL), department_name_en (VARCHAR 255, NULL), department_code (VARCHAR 50, UNIQUE, NOT NULL), active (TINYINT, default 1), created_at (DATETIME), updated_at (DATETIME)
    - Set table charset to utf8mb4 and collation to utf8mb4_unicode_ci
    - Add foreign key constraint on `college_id` referencing `tb_colleges.id` with CASCADE on delete and CASCADE on update
    - Add unique index on `department_code` column
    - Add index on `college_id` column
    - Use CodeIgniter 4 Forge class methods and `addForeignKey()`
    - _Requirements: 2.1, 2.2, 2.3, 7.2, 7.3, 8.2_
  
  - [ ] 3.2 Implement `insertDepartmentData()` private method
    - Insert at least 12 departments mapped to the 4 colleges using batch insert
    - Computing College (college_id=1): قسم علوم الحاسب (DEPT001), قسم تقنية المعلومات (DEPT002), قسم أمن المعلومات (DEPT003), قسم علوم البيانات والذكاء الاصطناعي (DEPT004)
    - Administrative College (college_id=2): قسم إدارة الأعمال (DEPT005), قسم المحاسبة (DEPT006), قسم المالية (DEPT007), قسم التجارة الإلكترونية (DEPT008)
    - Health College (college_id=3): قسم الصحة العامة (DEPT009), قسم المعلوماتية الصحية (DEPT010)
    - Basic Sciences College (college_id=4): قسم الرياضيات (DEPT011), قسم اللغة الإنجليزية (DEPT012)
    - Each record includes: college_id, department_name_ar, department_name_en, department_code, active=1, created_at, updated_at
    - Use `$this->db->table('tb_departments')->insertBatch($departments)`
    - _Requirements: 2.4, 6.2, 6.4, 6.5, 8.2_

- [ ] 4. Implement courses table enhancements
  - [~] 4.1 Implement `addCoursesTableColumns()` private method
    - Check if `college_id` and `department_id` columns already exist using `$this->db->fieldExists()`
    - Add `college_id` column to `tb_courses` (INT(11), NULL, AFTER some_existing_column)
    - Add `department_id` column to `tb_courses` (INT(11), NULL, AFTER college_id)
    - Create foreign key constraint from `college_id` to `tb_colleges.id` with RESTRICT on delete and CASCADE on update
    - Create foreign key constraint from `department_id` to `tb_departments.id` with RESTRICT on delete and CASCADE on update
    - Add index on `college_id` column
    - Add index on `department_id` column
    - Use CodeIgniter 4 Forge class methods: `addColumn()`, `addForeignKey()`, `addKey()`
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6_

- [ ] 5. Implement migration up() method
  - [~] 5.1 Wire up migration execution in `up()` method
    - Check if `tb_colleges` table exists using `$this->db->tableExists()`, call `createCollegesTable()` if not
    - Check if colleges data exists, call `insertCollegeData()` if table is empty
    - Check if `tb_departments` table exists, call `createDepartmentsTable()` if not
    - Check if departments data exists, call `insertDepartmentData()` if table is empty
    - Call `addCoursesTableColumns()` to add foreign key columns to courses table
    - Ensure execution order: colleges → college data → departments → department data → courses columns
    - Wrap operations in try-catch for error handling and logging
    - _Requirements: 5.4, 1.4, 2.5_

- [ ] 6. Implement migration down() method
  - [~] 6.1 Implement rollback logic in `down()` method
    - Check if `college_id` and `department_id` columns exist on `tb_courses`
    - Drop foreign key constraints first: `tb_courses_college_id_foreign` and `tb_courses_department_id_foreign`
    - Drop `college_id` and `department_id` columns from `tb_courses` using `dropColumn()`
    - Check if `tb_departments` table exists, drop if exists using `dropTable()`
    - Check if `tb_colleges` table exists, drop if exists using `dropTable()`
    - Ensure reverse dependency order: courses columns → departments table → colleges table
    - Use CodeIgniter 4 Forge methods: `dropForeignKey()`, `dropColumn()`, `dropTable()`
    - _Requirements: 5.5, 1.4, 2.5, 3.7_

- [ ] 7. Test migration execution
  - [~] 7.1 Run migration and verify table creation
    - Execute migration using: `php spark migrate`
    - Verify `tb_colleges` table exists with correct structure and 4 records
    - Verify `tb_departments` table exists with correct structure and 12+ records
    - Verify `tb_courses` has new `college_id` and `department_id` columns with foreign keys
    - Check foreign key constraints are active using database inspection queries
    - Verify utf8mb4 character encoding on all new tables
    - _Requirements: 1.1, 1.2, 1.3, 2.1, 2.2, 2.4, 3.1, 3.2, 7.1, 7.2, 7.3_

- [ ] 8. Test migration rollback
  - [~] 8.1 Test rollback functionality
    - Execute rollback using: `php spark migrate:rollback`
    - Verify `college_id` and `department_id` columns removed from `tb_courses`
    - Verify `tb_departments` table dropped
    - Verify `tb_colleges` table dropped
    - Verify database state is back to pre-migration state
    - _Requirements: 1.4, 2.5, 3.7_

- [~] 9. Checkpoint - Final verification
  - Ensure all tests pass, review migration file, and ask the user if questions arise.

## Notes

- The migration file timestamp `2026-07-28-130000` should be after the most recent migration in the project
- All VARCHAR and TEXT columns use utf8mb4 character encoding with utf8mb4_unicode_ci collation to properly support Arabic text
- Foreign key constraints use RESTRICT on delete for courses table to prevent accidental deletion of colleges/departments that have associated courses
- Foreign key constraints use CASCADE on delete for departments → colleges to automatically remove departments when their parent college is deleted
- The `active` column allows soft deactivation without data loss (Requirements 8.3, 8.4)
- College codes follow format: COL001, COL002, COL003, COL004
- Department codes follow format: DEPT001 through DEPT012
- The migration is idempotent - it checks for existence before creating/modifying tables
- Validation logic for department-college consistency (Requirements 4.1, 4.2, 4.3) will be implemented at the application level in models/controllers, not in the migration itself
- Use CodeIgniter 4 Database Forge API for all schema modifications
- Migration must be tested both for up() and down() operations to ensure rollback works correctly

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1"] },
    { "id": 1, "tasks": ["2.1", "3.1"] },
    { "id": 2, "tasks": ["2.2", "3.2"] },
    { "id": 3, "tasks": ["4.1"] },
    { "id": 4, "tasks": ["5.1"] },
    { "id": 5, "tasks": ["6.1"] },
    { "id": 6, "tasks": ["7.1"] },
    { "id": 7, "tasks": ["8.1"] }
  ]
}
```
