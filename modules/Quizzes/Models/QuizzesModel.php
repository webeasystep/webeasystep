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
        return $this->select('tb_quizzes.*, tb_courses.course_title')
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
            'AVG(time_taken_seconds) as average_time'
        ])
        ->where('quiz_id', $quizId)
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
            'tb_quiz_attempts.time_taken_seconds',
            'tb_quiz_attempts.attempt_date',
            'users.full_name',
            'users.username'
        ])
        ->join('users', 'users.id = tb_quiz_attempts.user_id')
        ->where('tb_quiz_attempts.quiz_id', $quizId)
        ->orderBy('tb_quiz_attempts.score', 'DESC')
        ->orderBy('tb_quiz_attempts.time_taken_seconds', 'ASC')
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
    public function validateQuizJSON($jsonData)
    {
        // Check required field: quiz_title
        if (!isset($jsonData['quiz_title']) || empty($jsonData['quiz_title'])) {
            return false;
        }

        // Accept both 'questions' and 'quiz_questions' as the questions key
        $questions = $jsonData['questions'] ?? $jsonData['quiz_questions'] ?? null;

        if (!is_array($questions) || empty($questions)) {
            return false;
        }

        // Validate questions structure
        // Types that don't require correct_answer
        $noAnswerTypes = ['essay'];
        // Types that require options array
        $requiresOptions = ['multiple_choice', 'single_choice'];

        foreach ($questions as $index => $question) {
            // Check for question_text
            if (!isset($question['question_text']) || empty($question['question_text'])) {
                return false;
            }

            if (!isset($question['question_type'])) {
                return false;
            }

            // options are only required for choice-based questions
            if (in_array($question['question_type'], $requiresOptions) && !isset($question['options'])) {
                return false;
            }

            // correct_answer is NOT required for essay questions
            if (!in_array($question['question_type'], $noAnswerTypes) && !isset($question['correct_answer'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Import quiz from JSON data
     */
    public function importFromJSON($jsonData)
    {
        try {
            log_message('info', '[QUIZ_MODEL] Starting importFromJSON');
            log_message('info', '[QUIZ_MODEL] Input JSON keys: ' . json_encode(array_keys($jsonData)));
            
            // Log the questions data specifically
            if (isset($jsonData['questions'])) {
                log_message('info', '[QUIZ_MODEL] Questions count: ' . count($jsonData['questions']));
                foreach ($jsonData['questions'] as $index => $question) {
                    log_message('info', '[QUIZ_MODEL] Question ' . ($index + 1) . ' structure: ' . json_encode($question));
                }
            }
            
            // Accept both 'questions' and 'quiz_questions' as the questions key
            $questions = $jsonData['questions'] ?? $jsonData['quiz_questions'] ?? [];

            // Accept both 'time_limit' and 'time_limit_minutes' as the time limit key
            $timeLimit = $jsonData['time_limit_minutes'] ?? $jsonData['time_limit'] ?? 30;

            // Accept both 'quiz_description' and 'quiz_desc' as the description key
            $desc = $jsonData['quiz_desc'] ?? $jsonData['quiz_description'] ?? '';

            // Prepare quiz data
            $quizData = [
                'course_id'               => $jsonData['course_id'] ?? 1,
                'quiz_title'              => $jsonData['quiz_title'],
                'quiz_desc'               => $desc,
                'time_limit_minutes'      => $timeLimit,
                'passing_score'           => $jsonData['passing_score'] ?? 70.00,
                'max_attempts'            => $jsonData['max_attempts'] ?? 3,
                'shuffle_questions'       => $jsonData['shuffle_questions'] ?? 0,
                'shuffle_answers'         => $jsonData['shuffle_answers'] ?? 0,
                'show_results'            => $jsonData['show_results'] ?? 1,
                'show_results_immediately' => $jsonData['show_results_immediately'] ?? 1,
                'active'                  => 1,
                'quiz_questions'          => json_encode($questions)
            ];

            log_message('info', '[QUIZ_MODEL] Prepared quiz data: ' . json_encode($quizData));
            log_message('info', '[QUIZ_MODEL] Quiz questions JSON: ' . $quizData['quiz_questions']);
            
            $result = $this->insert($quizData);
            log_message('info', '[QUIZ_MODEL] Insert result: ' . ($result ? 'success (ID: ' . $result . ')' : 'failed'));
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', '[QUIZ_MODEL] Failed to import quiz from JSON: ' . $e->getMessage());
            log_message('error', '[QUIZ_MODEL] Exception trace: ' . $e->getTraceAsString());
            return false;
        }
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
