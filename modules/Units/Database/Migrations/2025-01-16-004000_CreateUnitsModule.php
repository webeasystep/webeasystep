<?php

namespace Modules\Units\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitsModule extends Migration
{
    public function up()
    {
        // Create tb_units table if it doesn't exist
        if (!$this->db->tableExists('tb_units')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'section_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'unit_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'unit_desc' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'video_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'video_duration' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                ],
                'sort_order' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 1,
                ],
                'is_free' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'comment' => '1 = Free preview unit, 0 = Paid content'
                ],
                'active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                    'comment' => '1 = Active, 0 = Inactive'
                ],
                'unit_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => 'video',
                    'comment' => 'Type of unit: video, text, quiz, assignment, etc.'
                ],
                'content_data' => [
                    'type' => 'JSON',
                    'null' => true,
                    'comment' => 'Additional content data in JSON format'
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
            $this->forge->addKey('section_id');
            $this->forge->addKey('sort_order');
            $this->forge->addKey('active');
            $this->forge->addKey(['section_id', 'sort_order']);
            
            // Add foreign key constraint if sections table exists
            if ($this->db->tableExists('tb_sections')) {
                $this->forge->addForeignKey('section_id', 'tb_sections', 'id', 'CASCADE', 'CASCADE');
            }
            
            $this->forge->createTable('tb_units');
        }

        // Create tb_unit_items table if it doesn't exist
        if (!$this->db->tableExists('tb_unit_items')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'unit_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'item_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['video', 'quiz', 'page'],
                    'default' => 'video',
                ],
                'item_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'video_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'video_title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'video_duration' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                ],
                'video_thumbnail' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                    'null' => true,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'content_data' => [
                    'type' => 'JSON',
                    'null' => true,
                ],
                'quiz_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'page_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'sort_order' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 1,
                ],
                'is_active' => [
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
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('unit_id');
            $this->forge->addKey('item_type');
            $this->forge->addKey('sort_order');
            
            // Add foreign key constraint if units table exists
            if ($this->db->tableExists('tb_units')) {
                $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'CASCADE');
            }
            
            $this->forge->createTable('tb_unit_items');
        }

        // Create tb_unit_quizzes table if it doesn't exist
        if (!$this->db->tableExists('tb_unit_quizzes')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'unit_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'quiz_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'sort_order' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 1,
                    'comment' => 'Order of quiz within unit'
                ],
                'is_required' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                    'comment' => '1 = Required to complete unit, 0 = Optional'
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
            $this->forge->addKey(['unit_id', 'quiz_id'], false, true); // Unique constraint
            $this->forge->addKey('unit_id');
            $this->forge->addKey('quiz_id');
            
            // Add foreign key constraints if tables exist
            if ($this->db->tableExists('tb_units')) {
                $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'CASCADE');
            }
            if ($this->db->tableExists('tb_quizzes')) {
                $this->forge->addForeignKey('quiz_id', 'tb_quizzes', 'id', 'CASCADE', 'CASCADE');
            }
            
            $this->forge->createTable('tb_unit_quizzes');
        }

        // Add indexes for performance optimization
        $this->addOptimizationIndexes();
    }

    public function down()
    {
        // Drop tables in reverse order due to foreign key constraints
        $this->forge->dropTable('tb_unit_quizzes', true);
        $this->forge->dropTable('tb_unit_items', true);
        $this->forge->dropTable('tb_units', true);
    }

    /**
     * Add performance optimization indexes
     */
    private function addOptimizationIndexes()
    {
        // Add composite indexes for common queries
        if ($this->db->tableExists('tb_units')) {
            try {
                // Index for getting active units by section
                $this->db->query('CREATE INDEX idx_units_section_active ON tb_units (section_id, active)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
            
            try {
                // Index for ordering units within sections
                $this->db->query('CREATE INDEX idx_units_section_sort ON tb_units (section_id, sort_order)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
            
            try {
                // Index for preview units
                $this->db->query('CREATE INDEX idx_units_preview ON tb_units (is_free, active)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
            
            try {
                // Index for unit type filtering
                $this->db->query('CREATE INDEX idx_units_type ON tb_units (unit_type, active)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        }

        if ($this->db->tableExists('tb_unit_quizzes')) {
            try {
                // Index for getting quizzes by unit
                $this->db->query('CREATE INDEX idx_unit_quizzes_unit ON tb_unit_quizzes (unit_id, sort_order)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
            
            try {
                // Index for getting units by quiz
                $this->db->query('CREATE INDEX idx_unit_quizzes_quiz ON tb_unit_quizzes (quiz_id)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        }
    }
}