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
        'score',
        'time_taken_seconds',
        'is_passed',
        'user_answers',
        'quiz_questions',
        'attempt_date',
        'started_at',
        'submitted_at'
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
     * Note: Since there's no status column, we'll check for recent attempts without completion
     */
    public function getActiveAttempt($userId, $quizId)
    {
        // For now, return null since we don't have a status column to track active attempts
        // This method would need to be redesigned based on actual table structure
        return null;
    }

    /**
     * Get user's best score for a quiz
     */
    public function getUserBestScore($userId, $quizId)
    {
        $result = $this->select('MAX(score) as best_score')
                       ->where('user_id', $userId)
                       ->where('quiz_id', $quizId)
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
                    ->orderBy('attempt_date', 'ASC')
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
            'users.username'
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
            'AVG(score) as average_score',
            'MAX(score) as best_score'
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
            'AVG(score) as average_score',
            'MAX(score) as highest_score',
            'MIN(score) as lowest_score',
            'AVG(time_taken_seconds) as average_time'
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
     * Note: This method is disabled since there's no status column
     */
    public function cleanupExpiredAttempts()
    {
        // This method would need to be redesigned based on actual table structure
        return false;
    }

    /**
     * Get top performers for a quiz
     */
    public function getTopPerformers($quizId, $limit = 10)
    {
        return $this->select([
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
        // Since we don't have status column, we'll consider all attempts as completed
        return 100;
    }

    /**
     * Get average completion time for a quiz
     */
    public function getAverageCompletionTime($quizId)
    {
        $result = $this->select('AVG(time_taken_seconds) as avg_time')
                       ->where('quiz_id', $quizId)
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
            'users.username'
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
