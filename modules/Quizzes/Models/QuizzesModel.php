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
     * Supported question types
     */
    public const SUPPORTED_TYPES = ['single_choice', 'multiple_choice', 'true_false', 'essay', 'fill_in_blank'];

    /**
     * Types that require an options array
     */
    public const REQUIRES_OPTIONS = ['single_choice', 'multiple_choice'];

    /**
     * Types that do NOT require a correct_answer
     */
    public const NO_ANSWER_TYPES = ['essay', 'fill_in_blank'];

    /**
     * Validate quiz JSON structure — returns ['valid' => bool, 'errors' => [...]]
     */
    public function validateQuizJSON($jsonData): array
    {
        $errors = [];

        // ── 1. Quiz-level fields ──────────────────────────────────────────
        if (empty($jsonData['quiz_title'])) {
            $errors[] = 'حقل "quiz_title" مطلوب ولا يمكن أن يكون فارغاً.';
        }

        // Accept both key names for questions
        $questions = $jsonData['questions'] ?? $jsonData['quiz_questions'] ?? null;

        if (!is_array($questions) || empty($questions)) {
            $errors[] = 'يجب أن يحتوي الملف على مفتاح "questions" أو "quiz_questions" وأن يكون مصفوفة غير فارغة.';
            return ['valid' => false, 'errors' => $errors];
        }

        if (count($questions) < 1) {
            $errors[] = 'يجب أن يحتوي الاختبار على سؤال واحد على الأقل.';
        }

        if (count($questions) > 200) {
            $errors[] = 'لا يمكن أن يتجاوز عدد الأسئلة 200 سؤال.';
        }

        $passingScore = $jsonData['passing_score'] ?? null;
        if ($passingScore !== null && (!is_numeric($passingScore) || $passingScore < 1 || $passingScore > 100)) {
            $errors[] = '"passing_score" يجب أن يكون رقماً بين 1 و 100.';
        }

        $timeLimit = $jsonData['time_limit_minutes'] ?? $jsonData['time_limit'] ?? null;
        if ($timeLimit !== null && (!is_numeric($timeLimit) || $timeLimit < 1 || $timeLimit > 360)) {
            $errors[] = '"time_limit_minutes" يجب أن يكون رقماً بين 1 و 360 دقيقة.';
        }

        // ── 2. Question-level validation ──────────────────────────────────
        foreach ($questions as $i => $question) {
            $num = $i + 1;
            $prefix = "السؤال #{$num}";

            if (!is_array($question)) {
                $errors[] = "{$prefix}: يجب أن يكون كائن (object) وليس نصاً أو رقماً.";
                continue;
            }

            // question_text
            if (empty($question['question_text'])) {
                $errors[] = "{$prefix}: حقل \"question_text\" مطلوب ولا يمكن أن يكون فارغاً.";
            } elseif (mb_strlen(trim($question['question_text'])) < 5) {
                $errors[] = "{$prefix}: نص السؤال قصير جداً (5 أحرف على الأقل).";
            }

            // question_type
            if (empty($question['question_type'])) {
                $errors[] = "{$prefix}: حقل \"question_type\" مطلوب.";
                continue; // skip further checks without a type
            }

            $type = $question['question_type'];

            if (!in_array($type, self::SUPPORTED_TYPES)) {
                $errors[] = "{$prefix}: نوع السؤال \"{$type}\" غير مدعوم. الأنواع المدعومة: " . implode(', ', self::SUPPORTED_TYPES) . '.';
                continue;
            }

            // options validation
            if (in_array($type, self::REQUIRES_OPTIONS)) {
                if (empty($question['options']) || !is_array($question['options'])) {
                    $errors[] = "{$prefix} ({$type}): حقل \"options\" مطلوب ويجب أن يكون مصفوفة.";
                } else {
                    $optCount = count($question['options']);
                    if ($type === 'single_choice' && $optCount < 2) {
                        $errors[] = "{$prefix}: أسئلة الاختيار من متعدد تحتاج خيارَين على الأقل (وُجد {$optCount}).";
                    }
                    if ($type === 'multiple_choice' && $optCount < 2) {
                        $errors[] = "{$prefix}: أسئلة الاختيار المتعدد تحتاج خيارَين على الأقل (وُجد {$optCount}).";
                    }
                    if ($optCount > 10) {
                        $errors[] = "{$prefix}: عدد الخيارات لا يجب أن يتجاوز 10 (وُجد {$optCount}).";
                    }
                    // Each option must be a non-empty string
                    foreach ($question['options'] as $oi => $opt) {
                        if (!is_string($opt) && !is_numeric($opt)) {
                            $errors[] = "{$prefix}: الخيار #{$oi} يجب أن يكون نصاً.";
                        } elseif (empty(trim((string)$opt))) {
                            $errors[] = "{$prefix}: الخيار #{$oi} لا يمكن أن يكون فارغاً.";
                        }
                    }
                }

                // correct_answer must be present and valid index
                if (!isset($question['correct_answer'])) {
                    $errors[] = "{$prefix} ({$type}): حقل \"correct_answer\" مطلوب.";
                } else {
                    $options = $question['options'] ?? [];
                    $ca = $question['correct_answer'];

                    if ($type === 'single_choice') {
                        // correct_answer can be an index (int) or the option text
                        if (is_int($ca) && ($ca < 0 || $ca >= count($options))) {
                            $errors[] = "{$prefix}: \"correct_answer\" ({$ca}) خارج نطاق الخيارات المتاحة (0-" . (count($options) - 1) . ').';
                        }
                    }

                    if ($type === 'multiple_choice') {
                        if (!is_array($ca)) {
                            $errors[] = "{$prefix}: \"correct_answer\" في أسئلة الاختيار المتعدد يجب أن يكون مصفوفة.";
                        } elseif (empty($ca)) {
                            $errors[] = "{$prefix}: \"correct_answer\" يجب أن يحتوي على إجابة صحيحة واحدة على الأقل.";
                        }
                    }
                }
            }

            // true_false correct_answer
            if ($type === 'true_false') {
                if (!isset($question['correct_answer'])) {
                    $errors[] = "{$prefix}: \"correct_answer\" مطلوب لأسئلة صح/خطأ (true أو false).";
                } elseif (!in_array($question['correct_answer'], [true, false, 'true', 'false', 1, 0], true)) {
                    $errors[] = "{$prefix}: \"correct_answer\" في أسئلة صح/خطأ يجب أن يكون true أو false فقط.";
                }
            }

            // points
            if (isset($question['points'])) {
                $pts = $question['points'];
                if (!is_numeric($pts) || $pts < 0 || $pts > 1000) {
                    $errors[] = "{$prefix}: \"points\" يجب أن يكون رقماً بين 0 و 1000 (وُجد: {$pts}).";
                }
            }
        }

        return [
            'valid'  => empty($errors),
            'errors' => $errors,
        ];
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
