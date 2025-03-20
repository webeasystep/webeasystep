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
        'course_name', 'course_desc', 'image', 'sort',
        'price', 'is_free', 'active', 'course_structure', 'slug'
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'object';

    // Additional table references
    protected $enrollmentsTable        = 'tb_enrollments';
    protected $videoCompletionsTable  = 'tb_video_completions';

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
    public function getCourseBySlug(string $slug)
    {
        return $this->where('slug', $slug)
            ->where('active', 1)
            ->first();
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
     * Fetch all active courses a user is enrolled in (requires 'tb_enrollments')
     */
    public function getAllUserCourses(int $userId)
    {
        $builder = $this->db->table($this->enrollmentsTable);
        $builder->select('tb_courses.*');
        $builder->join($this->table, "{$this->table}.id = {$this->enrollmentsTable}.course_id");
        $builder->where("{$this->enrollmentsTable}.user_id", $userId);
        $builder->where("{$this->table}.active", 1);
        return $builder->get()->getResult(); // array of objects
    }

    /* ================== ENROLLMENT METHODS ================== */

    /**
     * Enroll a user in a course if not already enrolled.
     * Creates or reactivates a record in tb_enrollments.
     */
    public function enrollUser(int $userId, int $courseId)
    {
        $builder = $this->db->table($this->enrollmentsTable);

        // Check if already enrolled
        $existing = $builder->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->get()
            ->getRow();

        if ($existing) {
            // If previously 'cancelled', reactivate
            if ($existing->status === 'cancelled') {
                $builder->where('id', $existing->id)
                    ->update(['status' => 'active']);
            }
            return $existing->id; // Return the existing enrollment ID
        }

        // Otherwise, create a new enrollment
        $data = [
            'user_id'     => $userId,
            'course_id'   => $courseId,
            'enrolled_at' => date('Y-m-d H:i:s'),
            'status'      => 'active',
        ];
        $builder->insert($data);

        return $this->db->insertID(); // returns new enrollment ID
    }

    /**
     * Check if a user is enrolled in a course (status != 'cancelled')
     */
    public function isUserEnrolled(int $userId, int $courseId): bool
    {
        $builder = $this->db->table($this->enrollmentsTable);
        $row = $builder->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status !=', 'cancelled')
            ->get()
            ->getRow();
        return (bool) $row;
    }

    /**
     * Retrieve a single enrollment record
     */
    public function getEnrollment(int $userId, int $courseId)
    {
        $builder = $this->db->table($this->enrollmentsTable);
        return $builder->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status !=', 'cancelled')
            ->get()
            ->getRow();
    }

    /* ================== LESSON COMPLETION METHODS ================== */

    /**
     * Mark a video as completed for a given enrollment
     */
    public function markLessonComplete(int $enrollmentId, int $videoId)
    {
        $builder = $this->db->table($this->videoCompletionsTable);

        // Check if already completed
        $existing = $builder->where('enrollment_id', $enrollmentId)
            ->where('video_id', $videoId)
            ->get()
            ->getRow();
        if ($existing) {
            return $existing->id; // Already completed
        }

        // Otherwise, insert a new record
        $data = [
            'enrollment_id' => $enrollmentId,
            'video_id'     => $videoId,
            'completed_at'  => date('Y-m-d H:i:s'),
        ];
        $builder->insert($data);

        return $this->db->insertID();
    }

    /**
     * Count how many videos are completed for a given enrollment
     */
    public function countCompletedLessons(int $enrollmentId): int
    {
        $builder = $this->db->table($this->videoCompletionsTable);
        return $builder->where('enrollment_id', $enrollmentId)->countAllResults();
    }
    public function getCompletedLessonIDs(int $enrollmentId): array
    {
        $builder = $this->db->table($this->videoCompletionsTable);
        $builder->select('video_id');
        $builder->where('enrollment_id', $enrollmentId);
        $query = $builder->get();

        $videoIDs = [];
        foreach ($query->getResultArray() as $row) {
            $videoIDs[] = (int) $row['video_id'];
        }
        return $videoIDs;
    }

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
                'section_id'    => $sectionCounter,
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
     * Example: compute user's progress in a course (videos completed / total).
     */
     function calculateProgress(object $course, object $enrollment): int
    {
        // decode structure as array
        $structure = json_decode($course->course_structure ?? '[]', true);
        if (!$structure) {
            return 0;
        }

        // total videos
        $totalLessons = 0;
        foreach ($structure as $section) {
            if (!empty($section['videos'])) {
                $totalLessons += count($section['videos']);
            }
        }

        // how many completed
        $completedCount = $this->coursesModel->countCompletedLessons($enrollment->id);

        if ($totalLessons === 0) {
            return 0;
        }
        return (int) round(($completedCount / $totalLessons) * 100);
    }

}
