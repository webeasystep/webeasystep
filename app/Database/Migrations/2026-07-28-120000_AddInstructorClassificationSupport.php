<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Aligns user classification and instructor course ownership with the live schema.
 */
class AddInstructorClassificationSupport extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('user_type', 'users')) {
            $this->forge->addColumn('users', [
                'user_type' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                    'null' => false,
                    'after' => 'mobile',
                    'comment' => '1 = student, 2 = instructor',
                ],
            ]);
        }

        $this->db->query('UPDATE users SET user_type = 1 WHERE user_type IS NULL OR user_type NOT IN (1, 2)');

        if (! $this->hasIndex('users', 'idx_users_user_type')) {
            $this->db->query('ALTER TABLE users ADD INDEX idx_users_user_type (user_type)');
        }

        if (! $this->db->fieldExists('instructor_id', 'tb_courses')) {
            $this->forge->addColumn('tb_courses', [
                'instructor_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'course_price',
                    'comment' => 'Assigned instructor user ID',
                ],
            ]);
        }

        if (! $this->hasIndex('tb_courses', 'idx_tb_courses_instructor_id')) {
            $this->db->query('ALTER TABLE tb_courses ADD INDEX idx_tb_courses_instructor_id (instructor_id)');
        }
    }

    public function down()
    {
        if ($this->hasIndex('tb_courses', 'idx_tb_courses_instructor_id')) {
            $this->db->query('ALTER TABLE tb_courses DROP INDEX idx_tb_courses_instructor_id');
        }

        if ($this->db->fieldExists('instructor_id', 'tb_courses')) {
            $this->forge->dropColumn('tb_courses', 'instructor_id');
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        return $this->db->query("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName])->getNumRows() > 0;
    }
}
