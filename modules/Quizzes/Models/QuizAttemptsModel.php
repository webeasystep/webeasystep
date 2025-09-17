<?php

namespace Modules\Quizzes\Models;

use App\Models\BaseModel;

class QuizAttemptsModel extends BaseModel
{
    protected $table = 'tb_quiz_attempts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'answers_data',
        'score',
        'total_questions',
        'correct_answers',
        'time_taken',
        'status',
        'started_at',
        'completed_at',
        'ip_address',
        'user_agent'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get user's attempt count for a quiz
     */
    public function getUserAttemptCount($userId, $quizId)
    {
        return $this->where('user_id', $userId)
                    ->where('quiz_id', $quizId)
                    ->countAllResults();
    }

    /**
     * Get user's active attempt for a quiz
     */
    public function getActiveAttempt($userId, $quizId)
    {
        return $this->where('user_id', $userId)
                    ->where('quiz_id', $quizId)
                    ->where('status', 'in_progress')
                    ->first();
    }

    /**
     * Get user's best score for a quiz
     */
    public function getUserBestScore($userId, $quizId)
    {
        $result = $this->select('MAX(score) as best_score')
                       ->where('user_id', $userId)
                       ->where('quiz_id', $quizId)
                       ->where('status', 'completed')
                       ->first();

        return $result ? $result->best_score : 0;
    }

    /**
     * Get user's latest attempt for a quiz
     */
    public function getUserLatestAttempt($userId, $quizId)
    {
        return $this->where('user_id', $userId)
                    ->where('quiz_id', $quizId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    /**
     * Get all user attempts for a quiz
     */
    public function getUserAttempts($userId, $quizId)
    {
        return $this->where('user_id', $userId)
                    ->where('quiz_id', $quizId)
                    ->orderBy('attempt_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get quiz attempts with user info for admin
     */
    public function getQuizAttemptsWithUsers($quizId, $limit = null)
    {
        $builder = $this->select([
            'tb_quiz_attempts.*',
            'users.full_name',
            'users.username',
            'users.email'
        ])
        ->join('users', 'users.id = tb_quiz_attempts.user_id')
        ->where('tb_quiz_attempts.quiz_id', $quizId)
        ->orderBy('tb_quiz_attempts.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get user's quiz history
     */
    public function getUserQuizHistory($userId, $limit = null)
    {
        $builder = $this->select([
            'tb_quiz_attempts.*',
            'tb_quizzes.quiz_title',
            'tb_courses.course_title'
        ])
        ->join('tb_quizzes', 'tb_quizzes.id = tb_quiz_attempts.quiz_id')
        ->join('tb_courses', 'tb_courses.id = tb_quizzes.course_id')
        ->where('tb_quiz_attempts.user_id', $userId)
        ->orderBy('tb_quiz_attempts.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get attempt statistics for a user
     */
    public function getUserAttemptStats($userId)
    {
        return $this->select([
            'COUNT(*) as total_attempts',
            'COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_attempts',
            'COUNT(CASE WHEN status = "in_progress" THEN 1 END) as active_attempts',
            'COUNT(CASE WHEN status = "expired" THEN 1 END) as expired_attempts',
            'AVG(CASE WHEN status = "completed" THEN score END) as average_score',
            'MAX(CASE WHEN status = "completed" THEN score END) as best_score'
        ])
        ->where('user_id', $userId)
        ->first();
    }

    /**
     * Get quiz performance analytics
     */
    public function getQuizAnalytics($quizId)
    {
        $builder = $this->db->table('tb_quiz_attempts');

        // Basic stats
        $basicStats = $builder->select([
            'COUNT(*) as total_attempts',
            'COUNT(DISTINCT user_id) as unique_users',
            'COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_attempts',
            'COUNT(CASE WHEN status = "in_progress" THEN 1 END) as active_attempts',
            'COUNT(CASE WHEN status = "expired" THEN 1 END) as expired_attempts',
            'AVG(CASE WHEN status = "completed" THEN score END) as average_score',
            'MAX(CASE WHEN status = "completed" THEN score END) as highest_score',
            'MIN(CASE WHEN status = "completed" THEN score END) as lowest_score',
            'AVG(CASE WHEN status = "completed" THEN time_taken END) as average_time'
        ])
        ->where('quiz_id', $quizId)
        ->get()
        ->getRow();

        // Score distribution
        $scoreDistribution = $builder->select([
            'CASE 
                WHEN score >= 90 THEN "A (90-100%)"
                WHEN score >= 80 THEN "B (80-89%)"
                WHEN score >= 70 THEN "C (70-79%)"
                WHEN score >= 60 THEN "D (60-69%)"
                ELSE "F (0-59%)"
            END as grade',
            'COUNT(*) as count'
        ])
        ->where('quiz_id', $quizId)
        ->where('status', 'completed')
        ->groupBy('grade')
        ->get()
        ->getResult();

        return [
            'basic_stats' => $basicStats,
            'score_distribution' => $scoreDistribution
        ];
    }

    /**
     * Get attempts by date range
     */
    public function getAttemptsByDateRange($startDate, $endDate, $quizId = null)
    {
        $builder = $this->where('created_at >=', $startDate)
                        ->where('created_at <=', $endDate);

        if ($quizId) {
            $builder->where('quiz_id', $quizId);
        }

        return $builder->orderBy('created_at', 'DESC')
                      ->findAll();
    }

    /**
     * Clean up expired attempts
     */
    public function cleanupExpiredAttempts()
    {
        // Mark attempts as expired if they've been in progress for more than 24 hours
        $expiredTime = date('Y-m-d H:i:s', strtotime('-24 hours'));

        return $this->where('status', 'in_progress')
                    ->where('started_at <', $expiredTime)
                    ->set('status', 'expired')
                    ->set('completed_at', date('Y-m-d H:i:s'))
                    ->update();
    }

    /**
     * Get top performers for a quiz
     */
    public function getTopPerformers($quizId, $limit = 10)
    {
        return $this->select([
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
        ->findAll();
    }

    /**
     * Check if user passed quiz
     */
    public function hasUserPassedQuiz($userId, $quizId, $passingScore = 70)
    {
        $bestScore = $this->getUserBestScore($userId, $quizId);
        return $bestScore >= $passingScore;
    }

    /**
     * Get completion rate for a quiz
     */
    public function getCompletionRate($quizId)
    {
        $totalAttempts = $this->where('quiz_id', $quizId)->countAllResults();
        $completedAttempts = $this->where('quiz_id', $quizId)
                                  ->where('status', 'completed')
                                  ->countAllResults();

        return $totalAttempts > 0 ? ($completedAttempts / $totalAttempts) * 100 : 0;
    }

    /**
     * Get average completion time for a quiz
     */
    public function getAverageCompletionTime($quizId)
    {
        $result = $this->select('AVG(time_taken) as avg_time')
                       ->where('quiz_id', $quizId)
                       ->where('status', 'completed')
                       ->first();

        return $result ? $result->avg_time : 0;
    }

    /**
     * Get recent quiz attempts
     */
    public function getRecentAttempts($limit = 10)
    {
        return $this->select([
            'tb_quiz_attempts.*',
            'tb_quizzes.quiz_title',
            'users.full_name',
            'users.username'
        ])
        ->join('tb_quizzes', 'tb_quizzes.id = tb_quiz_attempts.quiz_id')
        ->join('users', 'users.id = tb_quiz_attempts.user_id')
        ->orderBy('tb_quiz_attempts.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    /**
     * Get quiz attempts with quiz and user details
     */
    public function getQuizAttempts($quizId = null, $userId = null)
    {
        $builder = $this->select([
            'tb_quiz_attempts.*',
            'tb_quizzes.quiz_title',
            'tb_quizzes.passing_score',
            'users.full_name',
            'users.username',
            'users.email'
        ])
        ->join('tb_quizzes', 'tb_quizzes.id = tb_quiz_attempts.quiz_id')
        ->join('users', 'users.id = tb_quiz_attempts.user_id')
        ->orderBy('tb_quiz_attempts.created_at', 'DESC');

        if ($quizId) {
            $builder->where('tb_quiz_attempts.quiz_id', $quizId);
        }

        if ($userId) {
            $builder->where('tb_quiz_attempts.user_id', $userId);
        }

        return $builder->findAll();
    }
}
