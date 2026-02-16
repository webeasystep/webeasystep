<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create course enrollments table and add course_price to courses
 * This converts from unit-based to course-based enrollment system
 */
class CreateCourseEnrollments extends Migration
{
    public function up()
    {
        // Add course_price column to tb_courses if not exists
        if (!$this->db->fieldExists('course_price', 'tb_courses')) {
            $this->forge->addColumn('tb_courses', [
                'course_price' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0.00,
                    'after' => 'course_desc'
                ]
            ]);
        }

        // Create new course enrollments table
        if (!$this->db->tableExists('tb_course_enrollments')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'course_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'paid_amount' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0.00,
                ],
                'payment_method' => [
                    'type' => 'ENUM',
                    'constraint' => ['fawry', 'vodafone_cash', 'instapay', 'bank_transfer', 'credits', 'free'],
                    'default' => 'fawry',
                ],
                'payment_proof' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['pending', 'approved', 'rejected', 'expired'],
                    'default' => 'pending',
                ],
                'approved_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'approved_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'expires_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'notes' => [
                    'type' => 'TEXT',
                    'null' => true,
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
            $this->forge->addKey('user_id');
            $this->forge->addKey('course_id');
            $this->forge->addKey('status');
            $this->forge->addKey(['user_id', 'course_id']);
            $this->forge->createTable('tb_course_enrollments');
        }
    }

    public function down()
    {
        // Drop course_price column from tb_courses
        if ($this->db->fieldExists('course_price', 'tb_courses')) {
            $this->forge->dropColumn('tb_courses', 'course_price');
        }

        // Drop course enrollments table
        $this->forge->dropTable('tb_course_enrollments', true);
    }
}
