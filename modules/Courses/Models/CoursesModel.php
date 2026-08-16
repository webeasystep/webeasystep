<?php
namespace Modules\Courses\Models;

use App\Models\BaseModel;
use CodeIgniter\Database\BaseBuilder;

class CoursesModel extends BaseModel
{
    // Existing table for courses
    protected $table         = 'tb_courses';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'course_title', 'course_desc', 'short_desc', 'image', 'sort',
        'is_free', 'active', 'is_open', 'slug', 'course_price', 'college_id', 'department_id',
        'instructor_id', 'category_id', 'difficulty_level', 'language',
        'requirements', 'what_you_learn', 'enrollment_limit', 'intro_video_id', 'waiting_list',
        'telegram_link'
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'object';

    // Additional table references
    // Additional table references
    protected $enrollmentsTable        = 'tb_course_enrollments';

    /**
     * Fetch course by ID
     */
    public function getCourseById(int $id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Fetch course by slug (if you store 'slug' in DB)
     */
    public function getCourseBySlug(string $slug, bool $allowInactive = false)
    {
        $builder = $this->select('tb_courses.*, tb_colleges.college_name_ar, users.full_name as instructor_name, users.username as instructor_username, users.instructor_bio, users.avatar as instructor_avatar')
            ->join('tb_colleges', 'tb_colleges.id = tb_courses.college_id', 'left')
            ->join('users', 'users.id = tb_courses.instructor_id', 'left')
            ->where('tb_courses.slug', $slug);
            
        if (!$allowInactive) {
            $builder->where('tb_courses.active', 1);
        }
        
        return $builder->first();
    }

    /**
     * Insert a new course
     */
    public function insertCourse(array $data)
    {
        return $this->insert($data); // returns the new ID if $returnID = true
    }

    /**
     * Update an existing course
     */
    public function updateCourse(int $id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Fetch all courses that contain units a user has enrolled in
     */
    /**
     * Fetch all courses that a user has enrolled in
     */
    public function getAllUserCourses(int $userId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_courses.*, tb_course_enrollments.enrolled_at, tb_course_enrollments.status as enrollment_status');
        $builder->join('tb_course_enrollments', 'tb_course_enrollments.course_id = tb_courses.id');
        $builder->where('tb_course_enrollments.user_id', $userId);
        $builder->where('tb_course_enrollments.status', 'approved');
        $builder->where('tb_courses.active', 1);
        $builder->orderBy('tb_course_enrollments.enrolled_at', 'DESC');

        return $builder->get()->getResult();
    }

    /**
     * Get all active courses with enrollment counts and statistics
     */
    public function getAllCoursesWithStats(): array
    {
        $courses = $this->select('tb_courses.*, tb_colleges.college_name_ar as college_name')
                       ->join('tb_colleges', 'tb_colleges.id = tb_courses.college_id', 'left')
                       ->where('tb_courses.active', 1)
                       ->orderBy('tb_courses.sort', 'ASC')
                       ->findAll();

        foreach ($courses as &$course) {
            $course->enrollment_count = $this->getEnrollmentCount($course->id);
            $course->stats = $this->getCourseStats($course->id);
            $course->unit_count = $this->getUnitCount($course->id);
            $course->quiz_count = $this->getQuizCount($course->id);
        }

        return $courses;
    }

    /**
     * Get unit count for a course
     */
    public function getUnitCount(int $courseId): int
    {
        return $this->db->table('tb_units')
                       ->where('course_id', $courseId)
                       ->countAllResults();
    }

    /**
     * Get quiz count for a course
     */
    public function getQuizCount(int $courseId): int
    {
        return $this->db->table('tb_quizzes')
                       ->where('course_id', $courseId)
                       ->countAllResults();
    }

    /**
     * Get courses by category
     */
    public function getCoursesByCategory(int $categoryId): array
    {
        return $this->where('category_id', $categoryId)
                   ->where('active', 1)
                   ->orderBy('sort', 'ASC')
                   ->findAll();
    }

    /**
     * Get featured courses (top courses by sort order)
     */
    public function getFeaturedCourses(int $limit = 6): array
    {
        return $this->where('active', 1)
                   ->orderBy('sort', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Search courses by title or description
     */
    public function searchCourses(string $query): array
    {
        return $this->like('course_title', $query)
                   ->orLike('course_desc', $query)
                   ->orLike('short_desc', $query)
                   ->where('active', 1)
                   ->orderBy('course_title', 'ASC')
                   ->findAll();
    }

    /* ================== ENROLLMENT METHODS ================== */

    /**
     * Enroll a user in a course if not already enrolled.
     * Creates or reactivates a record in tb_unit_enrollments.
     */
    /**
     * Enroll a user in a course if not already enrolled.
     * Creates or reactivates a record in tb_course_enrollments.
     */
    public function enrollUser(int $userId, int $courseId, string $paymentMethod = 'free', float $amount = 0.00)
    {
        $builder = $this->db->table($this->enrollmentsTable);

        // Check if already enrolled
        $existing = $builder->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->get()
            ->getRow();

        if ($existing) {
            // If previously 'cancelled' or 'pending', reactivate/update
            if ($existing->status !== 'approved') {
                 $builder->where('id', $existing->id)
                    ->update([
                        'status' => 'approved',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            return $existing->id; // Return the existing enrollment ID
        }

        // Otherwise, create a new enrollment
        $data = [
            'user_id'        => $userId,
            'course_id'      => $courseId,
            'enrolled_at'    => date('Y-m-d H:i:s'),
            'status'         => 'approved',
            'payment_method' => $paymentMethod,
            'amount_paid'    => $amount
        ];
        $builder->insert($data);

        return $this->db->insertID(); // returns new enrollment ID
    }

    /**
     * Check if a user is enrolled in a course (status != 'cancelled')
     */
    public function isUserEnrolled(int $userId, int $courseId): bool
    {
        // Check if user has enrolled in the course via tb_course_enrollments
        $hasEnrollment = $this->db->table('tb_course_enrollments')
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->countAllResults() > 0;

        return $hasEnrollment;
    }

    /**
     * Retrieve a single enrollment record
     */
    /**
     * Retrieve a single enrollment record
     */
    public function getEnrollment(int $userId, int $courseId)
    {
        return $this->db->table($this->enrollmentsTable)
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'approved')
            ->get()
            ->getRow();
    }

    /* ================== LESSON COMPLETION METHODS ================== */



    /**
     * Flatten the entire video list from the structure to find next/prev easily.
     */
     function flattenLessons(array $preparedStructure): array
    {
        $allLessons = [];
        foreach ($preparedStructure as $section) {
            foreach ($section['videos'] as $video) {
                $allLessons[] = $video;
            }
        }
        return $allLessons;
    }

    /**
     * Build an array of sections/videos with IDs, titles, etc.
     */
     function prepareDynamicStructure(array $structureData): array
    {
        $dynamicStructure = [];
        $sectionCounter   = 1;

        foreach ($structureData as $sectionData) {
            $section = [
                'course_id'     => $unit->course_id,
                'section_title' => $sectionData['section_title'] ?? 'Section Title',
                'is_open'       => false,
                'videos'       => [],
            ];

            if (!empty($sectionData['videos']) && is_array($sectionData['videos'])) {
                $videoCounter = 1;
                foreach ($sectionData['videos'] as $videoData) {
                    $video = [
                        'id'      => $videoData['id'] ?? $videoCounter,
                        'video_title'   => $videoData['video_title'] ?? 'Lesson Title',
                        'video_desc'    => $videoData['video_desc']  ?? 'No description provided.',
                        'video_id'    => $videoData['video_id']  ?? '#',
                        'video_duration' => $videoData['video_duration'] ?? '0:00',
                        'is_preview'     => !empty($videoData['is_preview']),
                        'is_active'      => false,
                        'section_index'  => $sectionCounter,
                    ];
                    $section['videos'][] = $video;
                    $videoCounter++;
                }
            }

            $dynamicStructure[] = $section;
            $sectionCounter++;
        }

        return $dynamicStructure;
    }

    /****
     * Finds the first video that is NOT in $completedIDs.
     * If all are completed, returns the ID of the LAST video.
     ****/
     function findNextIncompleteLesson(array $flatLessons, array $completedIDs): int
    {
        foreach ($flatLessons as $video) {
            if (! in_array($video['id'], $completedIDs)) {
                // Return the first uncompleted video
                return $video['id'];
            }
        }
        // If everything is completed, return the last video's ID
        return end($flatLessons)['id'];
    }
    /**
     * Calculate user's progress in a course based on completed units.
     */
     function calculateProgress(object $course, object $enrollment): int
    {
        // Use Progress module to calculate completion percentage
        $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();

        // Get course completion percentage
        $completionPercentage = $progressModel->getCourseCompletionPercentage($enrollment->user_id, $course->id);

        return (int) $completionPercentage;
    }

    /**
     * Get enrollment count for a course
     */
    /**
     * Get enrollment count for a course
     */
    public function getEnrollmentCount(int $courseId): int
    {
        return $this->db->table($this->enrollmentsTable)
            ->where('course_id', $courseId)
            ->where('status', 'approved')
            ->countAllResults();
    }

    /**
     * Get course statistics
     */
    public function getCourseStats(int $courseId): array
    {
        $unitsModel = new \Modules\Units\Models\UnitsModel();
        $stats = $unitsModel->getCourseUnitStats($courseId);

        // Add enrollment statistics
        $stats['total_enrollments'] = $this->getEnrollmentCount($courseId);
        $stats['completion_rate'] = $this->getCourseCompletionRate($courseId);

        return $stats;
    }

    /**
     * Get course completion rate
     */
    /**
     * Get course completion rate
     */
    public function getCourseCompletionRate(int $courseId): float
    {
        // Get total approved enrollments
        $totalEnrollments = $this->getEnrollmentCount($courseId);
        
        if ($totalEnrollments == 0) {
            return 0.0;
        }

        // Logic to calculate average completion would go here.
        // For now returning 0.0 as per original placeholder logic but cleaned up.
        return 0.0;
    }

    /**
     * Get course with basic structure (removed sections functionality)
     */
    public function getCourseWithStructure(int $courseId): ?object
    {
        $course = $this->find($courseId);
        if (!$course) {
            return null;
        }

        $course->stats = $this->getCourseStats($courseId);

        return $course;
    }

    /**
     * Get popular courses based on unit enrollment count
     */
    public function getPopularCourses(int $limit = 10): array
    {
        // Get all courses with their enrollment counts
        $courses = $this->where('active', 1)
                        ->findAll();

        foreach ($courses as &$course) {
            $course->enrollment_count = $this->getEnrollmentCount($course->id);
        }

        // Sort by enrollment count descending
        usort($courses, function($a, $b) {
            return $b->enrollment_count <=> $a->enrollment_count;
        });

        return array_slice($courses, 0, $limit);
    }

    /**
     * Get recently added courses
     */
    public function getRecentCourses(int $limit = 6): array
    {
        return $this->where('active', 1)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get course analytics for admin dashboard
     */
    public function getCourseAnalytics(): array
    {
        $totalCourses = $this->where('active', 1)->countAllResults();
        $totalEnrollments = $this->db->table($this->enrollmentsTable)
                                   ->where('status', 'approved')
                                   ->countAllResults();

        $avgEnrollmentsPerCourse = $totalCourses > 0 ? round($totalEnrollments / $totalCourses, 2) : 0;

        // Get monthly enrollment trends
        $monthlyEnrollments = $this->db->table($this->enrollmentsTable)
                                      ->select('YEAR(enrolled_at) as year, MONTH(enrolled_at) as month, COUNT(*) as count')
                                      ->where('status', 'approved')
                                      ->where('enrolled_at >=', date('Y-m-d', strtotime('-12 months')))
                                      ->groupBy('YEAR(enrolled_at), MONTH(enrolled_at)')
                                      ->orderBy('year, month')
                                      ->get()
                                      ->getResultArray();

        return [
            'total_courses' => $totalCourses,
            'total_enrollments' => $totalEnrollments,
            'avg_enrollments_per_course' => $avgEnrollmentsPerCourse,
            'monthly_enrollments' => $monthlyEnrollments,
            'popular_courses' => $this->getPopularCourses(5)
        ];
    }

}
