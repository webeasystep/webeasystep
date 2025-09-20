<?php
namespace Modules\Units\Models;

use App\Models\BaseModel;

class UnitsModel extends BaseModel
{
    protected $table = 'tb_units';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'course_id', 'unit_name', 'unit_desc',
        'sort_order', 'active',
        'price', 'is_free'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    /**
     * Get unit details with custom query (Articles style)
     */
    public function detailCustomer($id = null)
    {
        $builder = $this->builder($this->table)
            ->select('tb_units.*, tb_courses.course_title')
            ->join('tb_courses', 'tb_courses.id = tb_units.course_id', 'left');

        if (empty($id)) {
            return $builder->orderBy('tb_units.sort_order', 'ASC')->get()->getResult();
        } else {
            return $builder->where('tb_units.id', $id)->get(1)->getRow();
        }
    }

    /**
     * Get unit by ID (Articles style)
     */
    public function getUnitById($id)
    {
        return $this->db->query("SELECT u.*, c.course_title, c.intro_video_id 
                                FROM tb_units u 
                                LEFT JOIN tb_courses c ON u.course_id = c.id 
                                WHERE u.id=?", [$id])->getRow();
    }

    /**
     * Custom insert method (Articles style)
     */
    public function insertUnit($data, bool $returnID = true)
    {
        $builder = $this->db->table($this->table);
        $builder->insert($data);

        if ($returnID) {
            return $this->db->insertID();
        }
        return true;
    }

    /**
     * Custom update method (Articles style)
     */
    public function updateUnit($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id', $id);
        return $builder->update($data);
    }

    /**
     * Get all units for a specific course
     */
    public function getUnitsByCourse(int $courseId): array
    {
        return $this->where('course_id', $courseId)
                   ->where('active', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->findAll();
    }

    /**
     * Get purchasable units with pricing
     */
    public function getPurchasableUnits($courseId = null)
    {
        $builder = $this->select('tb_units.*, tb_courses.course_title')
                       ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                       ->where('tb_units.active', 1)
                       // ->where('tb_units.is_purchasable', 1) // Column doesn't exist
                       ->where('tb_units.price IS NOT NULL');

        if ($courseId) {
            $builder->where('tb_units.course_id', $courseId);
        }

        return $builder->orderBy('tb_units.sort_order', 'ASC')
                      ->findAll();
    }

    /**
     * Check if user has access to unit (considering purchases)
     */
    public function hasUserAccess($userId, $unitId)
    {
        $unit = $this->find($unitId);
        if (!$unit || !$unit->active) {
            return false;
        }

        // If it's a preview unit, allow access
        if ($unit->is_free) {
            return true;
        }

        // Check if user has purchased this unit
        $purchasesModel = new UnitPurchasesModel();
        return $purchasesModel->hasUnitAccess($userId, $unitId);
    }

    /**
     * Get unit by ID with course validation
     */
    public function getUnitByIdWithCourse(int $unitId, int $courseId = null)
    {
        $builder = $this->where('id', $unitId)->where('active', 1);

        if ($courseId) {
            $builder->where('course_id', $courseId);
        }

        return $builder->first();
    }

    /**
     * Get all units for a course
     */
    public function getAllUnitsByCourse(int $courseId): array
    {
        return $this->select('tb_units.*')
                   ->where('tb_units.course_id', $courseId)
                   ->where('tb_units.active', 1)
                   ->orderBy('tb_units.sort_order', 'ASC')
                   ->findAll();
    }

    /**
     * Get unit with full details (course info)
     */
    public function getUnitWithDetails(int $unitId)
    {
        return $this->select('tb_units.*, tb_courses.course_title')
                   ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                   ->where('tb_units.id', $unitId)
                   ->where('tb_units.active', 1)
                   ->first();
    }

    /**
     * Get next unit in sequence
     */
    public function getNextUnit(int $currentUnitId, int $courseId): ?object
    {
        $currentUnit = $this->find($currentUnitId);
        if (!$currentUnit) {
            return null;
        }

        return $this->where('course_id', $courseId)
                   ->where('sort_order >', $currentUnit->sort_order)
                   ->where('active', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->first();
    }

    /**
     * Get previous unit in sequence
     */
    public function getPreviousUnit(int $currentUnitId, int $courseId): ?object
    {
        $currentUnit = $this->find($currentUnitId);
        if (!$currentUnit) {
            return null;
        }

        return $this->where('course_id', $courseId)
                   ->where('sort_order <', $currentUnit->sort_order)
                   ->where('active', 1)
                   ->orderBy('sort_order', 'DESC')
                   ->first();
    }

    /**
     * Get units with quiz assignments
     */
    public function getUnitsWithQuizzes(int $courseId = null): array
    {
        $builder = $this->select('tb_units.*, COUNT(tb_quizzes.id) as quiz_count')
                       ->join('tb_quizzes', 'tb_quizzes.course_id = tb_units.course_id', 'left')
                       ->where('tb_units.active', 1)
                       ->groupBy('tb_units.id');

        if ($courseId) {
            $builder->where('tb_units.course_id', $courseId);
        }

        return $builder->orderBy('tb_units.sort_order', 'ASC')
                      ->findAll();
    }

    /**
     * Get unit statistics
     */
    public function getUnitStats(int $unitId): array
    {
        $stats = [
            'total_views' => 0,
            'completions' => 0,
            'completion_rate' => 0,
            'average_time' => 0,
            'quiz_count' => 0
        ];

        // Get quiz count by course_id
        $unit = $this->find($unitId);
        $quizCount = 0;
        if ($unit) {
            $quizCount = $this->db->table('tb_quizzes')
                                 ->where('course_id', $unit->course_id)
                                 ->where('active', 1)
                                 ->countAllResults();
        }
        $stats['quiz_count'] = $quizCount;

        // If Progress module exists, get progress stats
        if (class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();

            $stats['total_views'] = $progressModel->where('unit_id', $unitId)->countAllResults(false);
            $stats['completions'] = $progressModel->where('unit_id', $unitId)
                                                  ->where('is_completed', 1)
                                                  ->countAllResults();

            if ($stats['total_views'] > 0) {
                $stats['completion_rate'] = round(($stats['completions'] / $stats['total_views']) * 100, 1);
            }
        }

        return $stats;
    }

    /**
     * Get course unit statistics
     */
    public function getCourseUnitStats(int $courseId): array
    {
        $units = $this->getUnitsByCourse($courseId);

        $stats = [
            'total_units' => count($units),
            'active_units' => 0,
            'preview_units' => 0,
            'units_with_quizzes' => 0,
            'total_duration' => 0
        ];

        foreach ($units as $unit) {
            if ($unit->active) {
                $stats['active_units']++;
            }
            if ($unit->is_free) {
                $stats['preview_units']++;
            }

            // Parse duration and add to total
            if (isset($unit->video_duration) && $unit->video_duration) {
                $stats['total_duration'] += $this->parseDurationToMinutes($unit->video_duration);
            }
        }

        // Get quizzes count for the course
        $stats['course_quizzes'] = $this->db->table('tb_quizzes')
                                           ->where('course_id', $courseId)
                                           ->where('active', 1)
                                           ->countAllResults();
        
        // Note: All units in a course have access to course quizzes
        $stats['units_with_quizzes'] = $stats['course_quizzes'] > 0 ? $stats['active_units'] : 0;

        return $stats;
    }

    /**
     * Parse duration string to minutes
     */
    private function parseDurationToMinutes(string $duration): int
    {
        $parts = explode(':', $duration);
        $minutes = 0;

        if (count($parts) == 2) {
            // Format: MM:SS
            $minutes = (int)$parts[0];
        } elseif (count($parts) == 3) {
            // Format: HH:MM:SS
            $minutes = ((int)$parts[0] * 60) + (int)$parts[1];
        }

        return $minutes;
    }

    /**
     * Create a new unit with proper sort order
     */
    public function createUnit(array $data): int
    {
        // Set default sort order if not provided
        if (!isset($data['sort_order'])) {
            $maxSort = $this->where('course_id', $data['course_id'])
                           ->selectMax('sort_order')
                           ->first();
            $data['sort_order'] = ($maxSort->sort_order ?? 0) + 1;
        }

        return $this->insert($data);
    }

    /**
     * Update unit sort orders
     */
    public function updateSortOrders(array $unitOrders): bool
    {
        $this->db->transStart();

        foreach ($unitOrders as $unitId => $sortOrder) {
            $this->update($unitId, ['sort_order' => $sortOrder]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Get units for DataTable with joins
     */
    public function getUnitsForDataTable(array $filters = []): array
    {
        $builder = $this->select('tb_units.*, tb_courses.course_title')
                       ->join('tb_courses', 'tb_courses.id = tb_units.course_id');

        // Apply filters
        if (!empty($filters['course_id'])) {
            $builder->where('tb_units.course_id', $filters['course_id']);
        }

        if (isset($filters['active'])) {
            $builder->where('tb_units.active', $filters['active']);
        }

        return $builder->orderBy('tb_courses.course_title', 'ASC')
                      ->orderBy('tb_units.sort_order', 'ASC')
                      ->findAll();
    }
}
