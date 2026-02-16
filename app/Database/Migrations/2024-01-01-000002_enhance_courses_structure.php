<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceCoursesStructure extends Migration
{
    public function up()
    {
        // Add new columns to existing tb_courses table
        $courseFields = [
            'short_desc' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Short course description for listings'
            ],
            'duration_hours' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Estimated course duration in hours'
            ],
            'difficulty_level' => [
                'type' => 'ENUM',
                'constraint' => ['beginner', 'intermediate', 'advanced'],
                'default' => 'beginner',
                'comment' => 'Course difficulty level'
            ],
            'prerequisites' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Course prerequisites description'
            ],
            'learning_outcomes' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Array of learning outcomes'
            ],
            'instructor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Reference to instructor user ID'
            ]
        ];
        
        $this->forge->addColumn('tb_courses', $courseFields);
        
        // Create units table for hierarchical structure
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to sections table'
            ],
            'unit_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Unit title'
            ],
            'unit_desc' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Unit description'
            ],
            'unit_type' => [
                'type' => 'ENUM',
                'constraint' => ['video', 'text', 'quiz', 'assignment'],
                'default' => 'video',
                'comment' => 'Type of unit content'
            ],
            'video_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Bunny.net video ID'
            ],
            'video_duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Video duration in seconds'
            ],
            'content' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'comment' => 'Text content or additional materials'
            ],
            'is_preview' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether unit is available as preview'
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Display order within section'
            ],
            'active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether unit is active'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('section_id', 'sections', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['section_id', 'sort_order'], false, false, 'idx_units_section_sort');
        $this->forge->addKey(['active'], false, false, 'idx_units_active');
        $this->forge->createTable('tb_units');
        
        // Enhance sections table
        $sectionFields = [
            'section_desc' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Section description'
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Display order within course'
            ],
            'is_locked' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether section requires previous completion'
            ]
        ];
        
        $this->forge->addColumn('sections', $sectionFields);
    }

    public function down()
    {
        // Drop units table
        $this->forge->dropTable('tb_units');
        
        // Drop added columns from courses
        $this->forge->dropColumn('tb_courses', [
            'short_desc', 'duration_hours', 'difficulty_level',
            'prerequisites', 'learning_outcomes', 'instructor_id'
        ]);
        
        // Drop added columns from sections
        $this->forge->dropColumn('sections', [
            'section_desc', 'sort_order', 'is_locked'
        ]);
    }
}