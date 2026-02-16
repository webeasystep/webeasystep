<?php

namespace Modules\Units\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveSectionsTable extends Migration
{
    public function up()
    {
        // First, add course_id column to tb_units table
        if ($this->db->tableExists('tb_units')) {
            $fields = [
                'course_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => false,
                    'after' => 'id'
                ]
            ];
            
            $this->forge->addColumn('tb_units', $fields);
            
            // Update existing units to get course_id from their sections
            if ($this->db->tableExists('tb_sections')) {
                $this->db->query("
                    UPDATE tb_units u 
                    INNER JOIN tb_sections s ON u.section_id = s.id 
                    SET u.course_id = s.course_id
                ");
            }
            
            // Add foreign key constraint for course_id
            if ($this->db->tableExists('tb_courses')) {
                $this->forge->addForeignKey('course_id', 'tb_courses', 'id', 'CASCADE', 'CASCADE');
            }
            
            // Drop the foreign key constraint for section_id first
            $this->forge->dropForeignKey('tb_units', 'tb_units_section_id_foreign');
            
            // Remove section_id column
            $this->forge->dropColumn('tb_units', 'section_id');
        }
        
        // Update tb_quizzes table to remove section_id references
        if ($this->db->tableExists('tb_quizzes')) {
            // Check if section_id column exists in tb_quizzes
            $fields = $this->db->getFieldData('tb_quizzes');
            $hasSectionId = false;
            foreach ($fields as $field) {
                if ($field->name === 'section_id') {
                    $hasSectionId = true;
                    break;
                }
            }
            
            if ($hasSectionId) {
                // Drop foreign key constraint if it exists
                try {
                    $this->forge->dropForeignKey('tb_quizzes', 'tb_quizzes_section_id_foreign');
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                
                // Remove section_id column from quizzes
                $this->forge->dropColumn('tb_quizzes', 'section_id');
            }
        }
        
        // Update videos table if it exists
        if ($this->db->tableExists('videos')) {
            // Add course_id if it doesn't exist
            $fields = $this->db->getFieldData('videos');
            $hasCourseId = false;
            foreach ($fields as $field) {
                if ($field->name === 'course_id') {
                    $hasCourseId = true;
                    break;
                }
            }
            
            if (!$hasCourseId) {
                $fields = [
                    'course_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true,
                        'after' => 'id'
                    ]
                ];
                $this->forge->addColumn('videos', $fields);
            }
            
            // Update existing videos to get course_id from their sections
            if ($this->db->tableExists('tb_sections')) {
                $this->db->query("
                    UPDATE videos v 
                    INNER JOIN tb_sections s ON v.section_id = s.id 
                    SET v.course_id = s.course_id
                ");
            }
            
            // Drop section_id foreign key and column
            try {
                $this->forge->dropForeignKey('videos', 'videos_section_id_foreign');
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }
            
            $this->forge->dropColumn('videos', 'section_id');
        }
        
        // Finally, drop the tb_sections table
        if ($this->db->tableExists('tb_sections')) {
            $this->forge->dropTable('tb_sections');
        }
        
        // Update indexes for better performance
        if ($this->db->tableExists('tb_units')) {
            // Drop old section-based index
            try {
                $this->db->query('DROP INDEX idx_units_section_sort ON tb_units');
            } catch (\Exception $e) {
                // Index might not exist
            }
            
            // Add new course-based index
            $this->db->query('CREATE INDEX idx_units_course_sort ON tb_units (course_id, sort_order)');
        }
    }
    
    public function down()
    {
        // Recreate tb_sections table
        if (!$this->db->tableExists('tb_sections')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'course_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'section_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'section_desc' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'sort_order' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0,
                ],
                'is_locked' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                ],
                'active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ]
            ]);
            
            $this->forge->addPrimaryKey('id');
            $this->forge->addKey(['course_id', 'sort_order']);
            
            if ($this->db->tableExists('tb_courses')) {
                $this->forge->addForeignKey('course_id', 'tb_courses', 'id', 'CASCADE', 'CASCADE');
            }
            
            $this->forge->createTable('tb_sections');
        }
        
        // Add section_id back to tb_units
        if ($this->db->tableExists('tb_units')) {
            $fields = [
                'section_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => false,
                    'after' => 'id'
                ]
            ];
            
            $this->forge->addColumn('tb_units', $fields);
            
            // Remove course_id column
            $this->forge->dropColumn('tb_units', 'course_id');
        }
        
        // Add section_id back to tb_quizzes if needed
        if ($this->db->tableExists('tb_quizzes')) {
            $fields = [
                'section_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ]
            ];
            
            $this->forge->addColumn('tb_quizzes', $fields);
        }
    }
}