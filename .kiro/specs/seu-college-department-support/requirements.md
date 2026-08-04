# Requirements Document

## Introduction

This feature adds organizational hierarchy support to the course management system for Saudi Electronic University (الجامعة السعودية الإلكترونية). The system will store and manage the relationship between courses, colleges (كليات), and departments (أقسام), enabling proper categorization and organization of academic content according to SEU's actual organizational structure.

## Glossary

- **System**: The course management application built with CodeIgniter 4
- **Migration_System**: The CodeIgniter 4 database migration subsystem
- **Courses_Table**: The existing `tb_courses` table in the MySQL database
- **Colleges_Table**: The new `tb_colleges` table storing SEU college/faculty data
- **Departments_Table**: The new `tb_departments` table storing SEU department data
- **College**: An academic college or faculty (كلية) at Saudi Electronic University
- **Department**: An academic department (قسم) within a college
- **SEU**: Saudi Electronic University (الجامعة السعودية الإلكترونية)
- **Course_Record**: A single row in the Courses_Table representing one course

## Requirements

### Requirement 1: Database Schema for Colleges

**User Story:** As a system administrator, I want to store college information in a dedicated table, so that courses can be properly categorized by their parent college.

#### Acceptance Criteria

1. THE Migration_System SHALL create the Colleges_Table with the following columns: id (primary key, auto-increment), college_name_ar (VARCHAR 255, NOT NULL), college_name_en (VARCHAR 255, NULL), college_code (VARCHAR 50, UNIQUE, NOT NULL), active (TINYINT, default 1), created_at (DATETIME), updated_at (DATETIME)

2. THE Migration_System SHALL create a unique index on the college_code column in the Colleges_Table

3. THE Migration_System SHALL populate the Colleges_Table with all SEU colleges including: كلية العلوم الإدارية والمالية, كلية الحوسبة والمعلوماتية, كلية الصحة والحياة, and كلية العلوم الأساسية والنظرية

4. WHEN the migration is rolled back, THE Migration_System SHALL drop the Colleges_Table and all its data

### Requirement 2: Database Schema for Departments

**User Story:** As a system administrator, I want to store department information linked to colleges, so that courses can be categorized by both college and department.

#### Acceptance Criteria

1. THE Migration_System SHALL create the Departments_Table with the following columns: id (primary key, auto-increment), college_id (INT, foreign key to Colleges_Table), department_name_ar (VARCHAR 255, NOT NULL), department_name_en (VARCHAR 255, NULL), department_code (VARCHAR 50, UNIQUE, NOT NULL), active (TINYINT, default 1), created_at (DATETIME), updated_at (DATETIME)

2. THE Migration_System SHALL create a foreign key constraint from college_id in Departments_Table to id in Colleges_Table with CASCADE on delete and CASCADE on update

3. THE Migration_System SHALL create an index on college_id in the Departments_Table

4. THE Migration_System SHALL populate the Departments_Table with SEU departments mapped to their respective colleges

5. WHEN the migration is rolled back, THE Migration_System SHALL drop the Departments_Table and all its data

### Requirement 3: Courses Table Enhancement

**User Story:** As a course administrator, I want each course to be linked to a college and department, so that I can organize and filter courses by organizational structure.

#### Acceptance Criteria

1. THE Migration_System SHALL add a college_id column to the Courses_Table with type INT, nullable, and a foreign key constraint to the Colleges_Table id column

2. THE Migration_System SHALL add a department_id column to the Courses_Table with type INT, nullable, and a foreign key constraint to the Departments_Table id column

3. THE Migration_System SHALL create an index on college_id in the Courses_Table

4. THE Migration_System SHALL create an index on department_id in the Courses_Table

5. THE foreign key constraints SHALL use RESTRICT on delete to prevent deletion of colleges or departments that have associated courses

6. THE foreign key constraints SHALL use CASCADE on update to maintain referential integrity when IDs change

7. WHEN the migration is rolled back, THE Migration_System SHALL drop the college_id and department_id columns from the Courses_Table

### Requirement 4: Data Integrity for College-Department Relationship

**User Story:** As a data administrator, I want to ensure departments always belong to valid colleges, so that the organizational hierarchy remains consistent.

#### Acceptance Criteria

1. WHEN a Course_Record is created or updated with a department_id, THE System SHALL verify the department's college_id matches the Course_Record's college_id

2. IF a Course_Record has a department_id but no college_id, THEN THE System SHALL reject the operation with a validation error

3. WHEN a department is assigned to a course, THE System SHALL allow the college_id to be NULL only if the department_id is also NULL

### Requirement 5: Migration File Naming and Structure

**User Story:** As a developer, I want the migration file to follow CodeIgniter 4 conventions, so that it integrates seamlessly with the existing migration system.

#### Acceptance Criteria

1. THE migration file SHALL be named using the pattern YYYY-MM-DD-HHMMSS_AddCollegeDepartmentSupport.php where the timestamp is greater than the most recent existing migration

2. THE migration class SHALL be named AddCollegeDepartmentSupport and extend CodeIgniter\Database\Migration

3. THE migration class SHALL implement both up() and down() methods

4. THE up() method SHALL execute all table creation and column addition operations in the correct dependency order: Colleges_Table first, Departments_Table second, Courses_Table modifications third

5. THE down() method SHALL execute all rollback operations in reverse dependency order: Courses_Table modifications first, Departments_Table second, Colleges_Table third

### Requirement 6: Saudi Electronic University Organizational Data

**User Story:** As a system administrator, I want accurate SEU organizational data pre-populated, so that the system reflects the actual university structure.

#### Acceptance Criteria

1. THE Migration_System SHALL insert at least 4 colleges with accurate Arabic names and English names for SEU

2. THE Migration_System SHALL insert at least 12 departments distributed across the colleges with accurate Arabic names and English names

3. THE college_code values SHALL use a consistent format such as COL001, COL002, etc.

4. THE department_code values SHALL use a consistent format such as DEPT001, DEPT002, etc.

5. THE inserted data SHALL include departments for كلية الحوسبة والمعلوماتية including at least: قسم علوم الحاسب, قسم تقنية المعلومات, and قسم أمن المعلومات

### Requirement 7: Character Encoding Support

**User Story:** As a system user, I want Arabic text to display correctly, so that college and department names are readable in Arabic.

#### Acceptance Criteria

1. THE Colleges_Table SHALL use utf8mb4 character encoding for all VARCHAR and TEXT columns

2. THE Departments_Table SHALL use utf8mb4 character encoding for all VARCHAR and TEXT columns

3. THE tables SHALL use utf8mb4_unicode_ci collation for proper Arabic text sorting and comparison

### Requirement 8: Active Status Management

**User Story:** As an administrator, I want to deactivate colleges or departments without deleting them, so that historical data remains intact while preventing new associations.

#### Acceptance Criteria

1. THE Colleges_Table SHALL include an active column with default value 1

2. THE Departments_Table SHALL include an active column with default value 1

3. WHEN a college or department is marked as inactive (active = 0), THE System SHALL allow existing course associations to remain

4. WHEN displaying colleges or departments for selection, THE System SHALL filter to show only active records (active = 1)
