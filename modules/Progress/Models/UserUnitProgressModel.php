<?php

namespace Modules\Progress\Models;

use App\Models\BaseModel;

class UserUnitProgressModel extends BaseModel
{
    protected $table         = 'tb_user_unit_progress';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id', 'unit_id', 'progress_percentage', 'watch_time',
        'is_completed', 'completed_at', 'last_position'
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'object';

    public function getUserUnitProgress(int $userId, int $unitId): ?object
    {
        return $this->where('user_id', $userId)
                   ->where('unit_id', $unitId)
                   ->first();
    }

    public function updateProgress(int $userId, int $unitId, array $progressData): bool
    {
        $existing = $this->getUserUnitProgress($userId, $unitId);

        if ($existing) {
            $updateData = [];

            if (isset($progressData['progress_percentage'])) {
                $updateData['progress_percentage'] = max(0, min(100, $progressData['progress_percentage']));
            }

            if (isset($progressData['watch_time'])) {
            $updateData['watch_time'] = max($existing->watch_time, $progressData['watch_time']);
            }

            if (isset($progressData['last_position_seconds'])) {
                $updateData['last_position_seconds'] = $progressData['last_position_seconds'];
            }

            if (isset($updateData['progress_percentage']) && $updateData['progress_percentage'] >= 100 && !$existing->is_completed) {
                $updateData['is_completed'] = 1;
                $updateData['completed_at'] = date('Y-m-d H:i:s');
            }

            return $this->update($existing->id, $updateData);
        } else {
            $newData = [
                'user_id' => $userId,
                'unit_id' => $unitId,
                'progress_percentage' => max(0, min(100, $progressData['progress_percentage'] ?? 0)),
                'watch_time' => $progressData['watch_time'] ?? 0,
                'last_position_seconds' => $progressData['last_position_seconds'] ?? 0,
                'is_completed' => 0
            ];

            if ($newData['progress_percentage'] >= 100) {
                $newData['is_completed'] = 1;
                $newData['completed_at'] = date('Y-m-d H:i:s');
            }

            return $this->insert($newData) !== false;
        }
    }

    /**
     * Mark unit as completed
     */
    public function markUnitCompleted(int $userId, int $unitId): bool
    {
        return $this->updateProgress($userId, $unitId, [
            'progress_percentage' => 100,
            'is_completed' => 1,
            'completed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get user's progress for all units in a course
     */
    public function getCourseProgress(int $userId, int $courseId): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_user_unit_progress.*, tb_units.unit_name as unit_title, tb_units.course_id');
        $builder->join('tb_units', 'tb_units.id = tb_user_unit_progress.unit_id');

        $builder->where('tb_user_unit_progress.user_id', $userId);
        $builder->where('tb_units.course_id', $courseId);
        $builder->orderBy('tb_units.sort_order');

        return $builder->get()->getResultArray();
    }

    /**
     * Get overall course completion percentage
     */
    public function getCourseCompletionPercentage(int $userId, int $courseId): float
    {
        // Get total units in course
        $totalUnits = $this->db->table('tb_units')

                              ->where('tb_units.course_id', $courseId)
                              ->where('tb_units.active', 1)

                              ->countAllResults();

        if ($totalUnits === 0) {
            return 0.0;
        }

        // Get completed units
        $completedUnits = $this->db->table($this->table)
                                  ->join('tb_units', 'tb_units.id = tb_user_unit_progress.unit_id')

                                  ->where('tb_user_unit_progress.user_id', $userId)
                                  ->where('tb_units.course_id', $courseId)
                                  ->where('tb_user_unit_progress.is_completed', 1)
                                  ->countAllResults();

        return round(($completedUnits / $totalUnits) * 100, 2);
    }

    /**
     * Get user's learning statistics
     */
    public function getUserLearningStats(int $userId): array
    {
        // Total watch time
        $totalWatchTime = $this->where('user_id', $userId)
                              ->selectSum('watch_time_seconds')
                              ->first()
                              ->watch_time_seconds ?? 0;

        // Total completed units
        $completedUnits = $this->where('user_id', $userId)
                              ->where('is_completed', 1)
                              ->countAllResults();

        // Units in progress
        $inProgressUnits = $this->where('user_id', $userId)
                               ->where('is_completed', 0)
                               ->where('progress_percentage >', 0)
                               ->countAllResults();

        // Average completion rate
        $avgProgress = $this->where('user_id', $userId)
                           ->selectAvg('progress_percentage')
                           ->first()
                           ->progress_percentage ?? 0;

        // Learning streak (consecutive days with progress)
        $streak = $this->calculateLearningStreak($userId);

        return [
            'total_watch_time' => $totalWatchTime,
            'total_watch_time_formatted' => $this->formatDuration($totalWatchTime),
            'completed_units' => $completedUnits,
            'in_progress_units' => $inProgressUnits,
            'average_progress' => round($avgProgress, 2),
            'learning_streak_days' => $streak
        ];
    }

    /**
     * Calculate learning streak in days
     */
    private function calculateLearningStreak(int $userId): int
    {
        $builder = $this->db->table($this->table);
        $builder->select('DATE(updated_at) as activity_date');
        $builder->where('user_id', $userId);
        $builder->where('updated_at >=', date('Y-m-d', strtotime('-30 days')));
        $builder->groupBy('DATE(updated_at)');
        $builder->orderBy('activity_date', 'DESC');

        $activities = $builder->get()->getResultArray();

        if (empty($activities)) {
            return 0;
        }

        $streak = 0;
        $currentDate = date('Y-m-d');

        foreach ($activities as $activity) {
            if ($activity['activity_date'] === $currentDate) {
                $streak++;
                $currentDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Get recent learning activity
     */
    public function getRecentActivity(int $userId, int $limit = 10): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_user_unit_progress.*, tb_units.unit_name as unit_title, tb_courses.course_title');
        $builder->join('tb_units', 'tb_units.id = tb_user_unit_progress.unit_id');
        $builder->join('tb_courses', 'tb_courses.id = tb_units.course_id');
        $builder->where('tb_user_unit_progress.user_id', $userId);
        $builder->orderBy('tb_user_unit_progress.updated_at', 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Get units that need attention (started but not completed)
     */
    public function getUnitsNeedingAttention(int $userId, int $limit = 5): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_user_unit_progress.*, tb_units.unit_name as unit_title, tb_courses.course_title, tb_courses.slug');
        $builder->join('tb_units', 'tb_units.id = tb_user_unit_progress.unit_id');
        $builder->join('tb_courses', 'tb_courses.id = tb_units.course_id');
        $builder->where('tb_user_unit_progress.user_id', $userId);
        $builder->where('tb_user_unit_progress.is_completed', 0);
        $builder->where('tb_user_unit_progress.progress_percentage >', 0);
        $builder->orderBy('tb_user_unit_progress.updated_at', 'ASC'); // Oldest first
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Get progress with detailed information
     */
    public function getProgressWithDetails(int $userId = null, int $courseId = null): array
    {
        $builder = $this->getProgressWithDetailsBuilder($userId, $courseId);
        return $builder->get()->getResultArray();
    }

    /**
     * Get progress query builder with detailed information
     */
    public function getProgressWithDetailsBuilder(int $userId = null, int $courseId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_user_unit_progress.*, tb_units.unit_name, tb_units.duration_hours, tb_courses.course_title, tb_courses.slug, users.username, users.email');
        $builder->join('tb_units', 'tb_units.id = tb_user_unit_progress.unit_id');
        $builder->join('tb_courses', 'tb_courses.id = tb_units.course_id');
        $builder->join('users', 'users.id = tb_user_unit_progress.user_id');

        if ($userId) {
            $builder->where('tb_user_unit_progress.user_id', $userId);
        }

        if ($courseId) {
            $builder->where('tb_units.course_id', $courseId);
        }

        $builder->orderBy('tb_courses.course_title', 'ASC');
        // Sections no longer exist - order by units only
        $builder->orderBy('tb_units.sort_order', 'ASC');

        return $builder;
    }

    /**
     * Format duration in seconds to human readable format
     */
    private function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $remainingSeconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $remainingSeconds);
        } else {
            return sprintf('%ds', $remainingSeconds);
        }
    }

    /**
     * Get progress analytics for admin dashboard
     */
    public function getProgressAnalytics(): array
    {
        // Total learning time across all users
        $totalLearningTime = $this->selectSum('watch_time')
                                 ->first()
                                 ->watch_time ?? 0;

        // Most active learners
        $activeUsers = $this->db->table($this->table)
                               ->select('user_id, SUM(watch_time) as total_time, COUNT(*) as units_accessed')
                               ->groupBy('user_id')
                               ->orderBy('total_time', 'DESC')
                               ->limit(10)
                               ->get()
                               ->getResultArray();

        // Completion rates by course
        $courseCompletionRates = $this->db->table('tb_courses')
                                          ->select('tb_courses.course_title, tb_courses.id,
                                                   COUNT(DISTINCT tb_user_unit_progress.user_id) as users_started,
                                                   COUNT(CASE WHEN tb_user_unit_progress.is_completed = 1 THEN 1 END) as units_completed,
                                                   COUNT(tb_units.id) as total_units')

                                          ->join('tb_units', 'tb_units.course_id = tb_courses.id')
                                          ->join('tb_user_unit_progress', 'tb_user_unit_progress.unit_id = tb_units.id', 'left')
                                          ->where('tb_courses.active', 1)
                                          ->groupBy('tb_courses.id')
                                          ->get()
                                          ->getResultArray();

        return [
            'total_learning_time' => $totalLearningTime,
            'total_learning_time_formatted' => $this->formatDuration($totalLearningTime),
            'active_users' => $activeUsers,
            'course_completion_rates' => $courseCompletionRates
        ];
    }
}
