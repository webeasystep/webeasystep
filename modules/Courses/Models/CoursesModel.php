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
    protected $lessonCompletionsTable  = 'tb_lesson_completions';

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
     * Mark a lesson as completed for a given enrollment
     */
    public function markLessonComplete(int $enrollmentId, int $lessonId)
    {
        $builder = $this->db->table($this->lessonCompletionsTable);

        // Check if already completed
        $existing = $builder->where('enrollment_id', $enrollmentId)
            ->where('lesson_id', $lessonId)
            ->get()
            ->getRow();
        if ($existing) {
            return $existing->id; // Already completed
        }

        // Otherwise, insert a new record
        $data = [
            'enrollment_id' => $enrollmentId,
            'lesson_id'     => $lessonId,
            'completed_at'  => date('Y-m-d H:i:s'),
        ];
        $builder->insert($data);

        return $this->db->insertID();
    }

    /**
     * Count how many lessons are completed for a given enrollment
     */
    public function countCompletedLessons(int $enrollmentId): int
    {
        $builder = $this->db->table($this->lessonCompletionsTable);
        return $builder->where('enrollment_id', $enrollmentId)->countAllResults();
    }
    public function getCompletedLessonIDs(int $enrollmentId): array
    {
        $builder = $this->db->table($this->lessonCompletionsTable);
        $builder->select('lesson_id');
        $builder->where('enrollment_id', $enrollmentId);
        $query = $builder->get();

        $lessonIDs = [];
        foreach ($query->getResultArray() as $row) {
            $lessonIDs[] = (int) $row['lesson_id'];
        }
        return $lessonIDs;
    }

}
