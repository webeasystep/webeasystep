<?php

namespace Modules\Quizzes\Models;

use App\Models\BaseModel;

class QuizzesModel extends BaseModel
{
    protected $table = 'tb_quizzes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'course_id',
        'quiz_title',
        'quiz_desc',
        'quiz_questions',
        'time_limit',
        'time_limit_minutes',
        'max_attempts',
        'passing_score',
        'shuffle_questions',
        'shuffle_answers',
        'show_results',
        'active'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get quiz by ID with course information
     */
    public function getQuizById($quizId)
    {
        return $this->select('tb_quizzes.*, tb_courses.course_title, tb_courses.course')
                    ->join('tb_courses', 'tb_courses.id = tb_quizzes.course_id')
                    ->where('tb_quizzes.id', $quizId)
                    ->where('tb_quizzes.active', 1)
                    ->first();
    }

    /**
     * Get quiz with course information (for admin view)
     */
    public function getQuizWithCourse($quizId)
    {
        return $this->select('tb_quizzes.*, tb_courses.course_title, tb_courses.slug')
                    ->join('tb_courses', 'tb_courses.id = tb_quizzes.course_id')
                    ->where('tb_quizzes.id', $quizId)
                    ->first();
    }

    /**
     * Get all quizzes for a course
     */
    public function getCourseQuizzes($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->where('active', 1)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get all quizzes for a course (including inactive ones for admin)
     */
    public function getAllCourseQuizzes($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Create a new quiz linked directly to a course
     */
    public function createCourseQuiz($data)
    {
        // Ensure course_id is set
        $quizData = [
            'course_id' => $data['course_id'],
            'quiz_title' => $data['quiz_title'],
            'quiz_desc' => $data['quiz_desc'] ?? '',
            'time_limit' => $data['time_limit'] ?? 30,
            'max_attempts' => $data['max_attempts'] ?? 3,
            'passing_score' => $data['passing_score'] ?? 70.00,
            'shuffle_questions' => $data['shuffle_questions'] ?? 0,
            'shuffle_answers' => $data['shuffle_answers'] ?? 0,
            'show_results' => $data['show_results'] ?? 1,
            'active' => $data['active'] ?? 1,
            'quiz_questions' => $data['quiz_questions'] ?? '[]'
        ];

        return $this->insert($quizData);
    }





    /**
     * Get quiz statistics
     */
    public function getQuizStats($quizId)
    {
        $builder = $this->db->table('tb_quiz_attempts');

        $stats = $builder->select([
            'COUNT(*) as total_attempts',
            'COUNT(DISTINCT user_id) as unique_users',
            'AVG(score) as average_score',
            'MAX(score) as highest_score',
            'MIN(score) as lowest_score',
            'AVG(time_taken) as average_time'
        ])
        ->where('quiz_id', $quizId)
        ->where('status', 'completed')
        ->get()
        ->getRow();

        return $stats;
    }

    /**
     * Get quiz leaderboard
     */
    public function getQuizLeaderboard($quizId, $limit = 10)
    {
        $builder = $this->db->table('tb_quiz_attempts');

        return $builder->select([
            'tb_quiz_attempts.score',
            'tb_quiz_attempts.time_taken',
            'tb_quiz_attempts.completed_at',
            'users.full_name',
            'users.username'
        ])
        ->join('users', 'users.id = tb_quiz_attempts.user_id')
        ->where('tb_quiz_attempts.quiz_id', $quizId)
        ->where('tb_quiz_attempts.status', 'completed')
        ->orderBy('tb_quiz_attempts.score', 'DESC')
        ->orderBy('tb_quiz_attempts.time_taken', 'ASC')
        ->limit($limit)
        ->get()
        ->getResult();
    }

    /**
     * Get all quizzes with course info for admin
     */
    public function getAllQuizzesWithCourse()
    {
        return $this->select('tb_quizzes.id,tb_quizzes.quiz_title, tb_quizzes.time_limit,max_attempts
                            passing_score , tb_quizzes.active ,tb_quizzes.created_at, tb_courses.course_title')
                    ->join('tb_courses', 'tb_courses.id = tb_quizzes.course_id')
                    ->orderBy('tb_quizzes.created_at', 'DESC');
    }

    /**
     * Search quizzes
     */
    public function searchQuizzes($keyword)
    {
        return $this->select('tb_quizzes.*, tb_courses.course_title')
                    ->join('tb_courses', 'tb_courses.id = tb_quizzes.course_id')
                    ->groupStart()
                        ->like('tb_quizzes.quiz_title', $keyword)
                        ->orLike('tb_quizzes.quiz_desc', $keyword)
                        ->orLike('tb_courses.course_title', $keyword)
                    ->groupEnd()
                    ->where('tb_quizzes.active', 1)
                    ->orderBy('tb_quizzes.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get quiz questions count
     */
    public function getQuestionsCount($quizId)
    {
        $quiz = $this->find($quizId);
        if (!$quiz) {
            return 0;
        }

        $questions = json_decode($quiz->quiz_questions, true);
        return is_array($questions) ? count($questions) : 0;
    }

    /**
     * Validate quiz JSON structure
     */
    public function validateQuizJson($jsonData)
    {
        $errors = [];

        // Check required fields
        $required = ['quiz_title', 'course_id', 'questions'];
        foreach ($required as $field) {
            if (!isset($jsonData[$field])) {
                $errors[] = "Missing required field: {$field}";
            }
        }

        // Validate questions structure
        if (isset($jsonData['questions']) && is_array($jsonData['questions'])) {
            foreach ($jsonData['questions'] as $index => $question) {
                if (!isset($question['question'])) {
                    $errors[] = "Question #{$index}: Missing question text";
                }

                if (!isset($question['type'])) {
                    $errors[] = "Question #{$index}: Missing question type";
                }

                if (in_array($question['type'], ['multiple_choice', 'single_choice']) && !isset($question['options'])) {
                    $errors[] = "Question #{$index}: Missing options for choice question";
                }

                if (!isset($question['correct_answer'])) {
                    $errors[] = "Question #{$index}: Missing correct answer";
                }
            }
        } else {
            $errors[] = "Questions must be an array";
        }

        return $errors;
    }

    /**
     * Duplicate quiz
     */
    public function duplicateQuiz($quizId, $newTitle = null)
    {
        $quiz = $this->find($quizId);
        if (!$quiz) {
            return false;
        }

        $quizArray = (array) $quiz;
        unset($quizArray['id']);
        unset($quizArray['created_at']);
        unset($quizArray['updated_at']);

        if ($newTitle) {
            $quizArray['quiz_title'] = $newTitle;
        } else {
            $quizArray['quiz_title'] = $quiz->quiz_title . ' (Copy)';
        }

        return $this->insert($quizArray);
    }

    /**
     * Get quiz difficulty analysis
     */
    public function getQuizDifficulty($quizId)
    {
        $builder = $this->db->table('tb_quiz_attempts');

        $stats = $builder->select('AVG(score) as avg_score, COUNT(*) as total_attempts')
                        ->where('quiz_id', $quizId)
                        ->where('status', 'completed')
                        ->get()
                        ->getRow();

        if (!$stats || $stats->total_attempts < 5) {
            return 'Unknown';
        }

        $avgScore = $stats->avg_score;

        if ($avgScore >= 80) {
            return 'Easy';
        } elseif ($avgScore >= 60) {
            return 'Medium';
        } else {
            return 'Hard';
        }
    }
}
