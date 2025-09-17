<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizzesSystem extends Migration
{
    public function up()
    {
        // Create quizzes table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_courses table'
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Optional reference to sections table'
            ],
            'quiz_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Quiz title'
            ],
            'quiz_desc' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Quiz description'
            ],
            'questions_data' => [
                'type' => 'JSON',
                'comment' => 'JSON structure containing all questions and answers'
            ],
            'time_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Time limit in minutes (null = no limit)'
            ],
            'max_attempts' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 3,
                'comment' => 'Maximum number of attempts allowed'
            ],
            'passing_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 70.00,
                'comment' => 'Minimum score to pass (percentage)'
            ],
            'shuffle_questions' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether to randomize question order'
            ],
            'shuffle_answers' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether to randomize answer options'
            ],
            'show_results' => [
                'type' => 'ENUM',
                'constraint' => ['immediate', 'after_completion', 'never'],
                'default' => 'after_completion',
                'comment' => 'When to show quiz results'
            ],
            'active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether quiz is active'
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
        $this->forge->addForeignKey('course_id', 'tb_courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'sections', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['course_id'], false, false, 'idx_quizzes_course');
        $this->forge->addKey(['active'], false, false, 'idx_quizzes_active');
        $this->forge->createTable('tb_quizzes');
        
        // Create quiz attempts table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'quiz_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to tb_quizzes table'
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to users table'
            ],
            'attempt_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Attempt sequence number for this user/quiz'
            ],
            'answers_data' => [
                'type' => 'JSON',
                'comment' => 'JSON structure containing user answers'
            ],
            'score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Final score percentage'
            ],
            'total_questions' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Total number of questions in attempt'
            ],
            'correct_answers' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Number of correct answers'
            ],
            'time_taken' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Time taken in seconds'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['in_progress', 'completed', 'abandoned', 'expired'],
                'default' => 'in_progress',
                'comment' => 'Attempt status'
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'comment' => 'When attempt was started'
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When attempt was completed'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'comment' => 'IP address of attempt'
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Browser user agent'
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
        $this->forge->addForeignKey('quiz_id', 'tb_quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['quiz_id', 'user_id'], false, false, 'idx_attempts_quiz_user');
        $this->forge->addKey(['user_id', 'status'], false, false, 'idx_attempts_user_status');
        $this->forge->addKey(['started_at'], false, false, 'idx_attempts_started');
        $this->forge->createTable('tb_quiz_attempts');
    }

    public function down()
    {
        $this->forge->dropTable('tb_quiz_attempts');
        $this->forge->dropTable('tb_quizzes');
    }
}