<?php

declare(strict_types=1);

namespace Tests\Database\Migrations;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Test for createCollegesTable() method in AddCollegeDepartmentSupport migration
 * 
 * Validates: Task 2.1 - Implement createCollegesTable() private method
 */
class CollegesTableTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = false;
    protected $migrateOnce = false;
    protected $refresh     = false;
    protected $namespace   = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clean up: drop the test table if it exists
        if ($this->db->tableExists('tb_colleges')) {
            $this->forge->dropTable('tb_colleges', true);
        }
    }

    /**
     * Test that createCollegesTable creates the table with correct structure
     * 
     * Validates: Requirements 1.1, 1.2, 7.1, 7.3, 8.1
     */
    public function testCreateCollegesTableStructure(): void
    {
        // Run the migration
        $migration = new \App\Database\Migrations\AddCollegeDepartmentSupport($this->forge);
        
        // Use reflection to call the private method
        $reflection = new \ReflectionClass($migration);
        $method = $reflection->getMethod('createCollegesTable');
        $method->setAccessible(true);
        $method->invoke($migration);

        // Verify table exists
        $this->assertTrue($this->db->tableExists('tb_colleges'), 'tb_colleges table should exist');

        // Get field data
        $fields = $this->db->getFieldData('tb_colleges');
        $fieldNames = array_column($fields, 'name');

        // Verify all required columns exist
        $this->assertContains('id', $fieldNames);
        $this->assertContains('college_name_ar', $fieldNames);
        $this->assertContains('college_name_en', $fieldNames);
        $this->assertContains('college_code', $fieldNames);
        $this->assertContains('active', $fieldNames);
        $this->assertContains('created_at', $fieldNames);
        $this->assertContains('updated_at', $fieldNames);

        // Verify field types and constraints
        foreach ($fields as $field) {
            switch ($field->name) {
                case 'id':
                    $this->assertEquals('int', $field->type);
                    $this->assertEquals('PRI', $field->primary_key);
                    break;
                case 'college_name_ar':
                    $this->assertEquals('varchar', $field->type);
                    $this->assertEquals(255, $field->max_length);
                    $this->assertFalse((bool)$field->nullable);
                    break;
                case 'college_name_en':
                    $this->assertEquals('varchar', $field->type);
                    $this->assertEquals(255, $field->max_length);
                    $this->assertTrue((bool)$field->nullable);
                    break;
                case 'college_code':
                    $this->assertEquals('varchar', $field->type);
                    $this->assertEquals(50, $field->max_length);
                    $this->assertFalse((bool)$field->nullable);
                    break;
                case 'active':
                    $this->assertEquals('tinyint', $field->type);
                    $this->assertEquals('1', $field->default);
                    break;
                case 'created_at':
                case 'updated_at':
                    $this->assertEquals('datetime', $field->type);
                    $this->assertTrue((bool)$field->nullable);
                    break;
            }
        }
    }

    /**
     * Test that college_code has unique index
     * 
     * Validates: Requirements 1.2
     */
    public function testCollegeCodeUniqueIndex(): void
    {
        // Run the migration
        $migration = new \App\Database\Migrations\AddCollegeDepartmentSupport($this->forge);
        
        // Use reflection to call the private method
        $reflection = new \ReflectionClass($migration);
        $method = $reflection->getMethod('createCollegesTable');
        $method->setAccessible(true);
        $method->invoke($migration);

        // Get index data
        $indexes = $this->db->getIndexData('tb_colleges');
        
        // Find the college_code unique index
        $collegeCodeIndex = null;
        foreach ($indexes as $index) {
            if (in_array('college_code', $index->fields)) {
                $collegeCodeIndex = $index;
                break;
            }
        }

        $this->assertNotNull($collegeCodeIndex, 'college_code should have an index');
        $this->assertEquals('unique', $collegeCodeIndex->type, 'college_code index should be unique');
    }

    /**
     * Test that table uses utf8mb4 charset
     * 
     * Validates: Requirements 7.1, 7.3
     */
    public function testTableCharset(): void
    {
        // Run the migration
        $migration = new \App\Database\Migrations\AddCollegeDepartmentSupport($this->forge);
        
        // Use reflection to call the private method
        $reflection = new \ReflectionClass($migration);
        $method = $reflection->getMethod('createCollegesTable');
        $method->setAccessible(true);
        $method->invoke($migration);

        // Query table information
        $query = $this->db->query("SELECT TABLE_COLLATION 
                                   FROM information_schema.TABLES 
                                   WHERE TABLE_SCHEMA = ? 
                                   AND TABLE_NAME = ?", 
                                  [$this->db->database, 'tb_colleges']);
        
        $result = $query->getRow();
        
        $this->assertNotNull($result);
        $this->assertEquals('utf8mb4_unicode_ci', $result->TABLE_COLLATION, 
                          'Table should use utf8mb4_unicode_ci collation');
    }

    /**
     * Test that duplicate college codes are rejected
     * 
     * Validates: Requirements 1.2 (unique constraint)
     */
    public function testDuplicateCollegeCodeRejected(): void
    {
        // Run the migration
        $migration = new \App\Database\Migrations\AddCollegeDepartmentSupport($this->forge);
        
        // Use reflection to call the private method
        $reflection = new \ReflectionClass($migration);
        $method = $reflection->getMethod('createCollegesTable');
        $method->setAccessible(true);
        $method->invoke($migration);

        // Insert first college
        $this->db->table('tb_colleges')->insert([
            'college_name_ar' => 'كلية الحوسبة',
            'college_code' => 'COL001',
            'active' => 1
        ]);

        // Try to insert duplicate college_code
        $this->expectException(\CodeIgniter\Database\Exceptions\DatabaseException::class);
        
        $this->db->table('tb_colleges')->insert([
            'college_name_ar' => 'كلية أخرى',
            'college_code' => 'COL001', // Duplicate
            'active' => 1
        ]);
    }
}
