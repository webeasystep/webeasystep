<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitIdToQuizzes extends Migration
{
    public function up()
    {
        // Add unit_id column to tb_quizzes table
        $fields = [
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Reference to tb_units table',
                'after' => 'section_id'
            ]
        ];
        
        $this->forge->addColumn('tb_quizzes', $fields);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'CASCADE', 'SET NULL');
        
        // Update existing quiz records to set unit_id based on section_id
        // This query will set unit_id to the first unit found in each section
        $this->db->query("
            UPDATE tb_quizzes q 
            LEFT JOIN (
                SELECT u.id as unit_id, u.section_id
                FROM tb_units u
                WHERE u.active = 1
                GROUP BY u.section_id
                HAVING MIN(u.sort_order)
            ) first_units ON first_units.section_id = q.section_id
            SET q.unit_id = first_units.unit_id
            WHERE q.section_id IS NOT NULL
        ");
    }
    
    public function down()
    {
        // Drop foreign key constraint first
        $this->forge->dropForeignKey('tb_quizzes', 'tb_quizzes_unit_id_foreign');
        
        // Drop the unit_id column
        $this->forge->dropColumn('tb_quizzes', 'unit_id');
    }
}