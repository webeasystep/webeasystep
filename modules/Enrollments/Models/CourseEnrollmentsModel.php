<?php

namespace Modules\Enrollments\Models;

use App\Models\BaseModel;

/**
 * Course Enrollments Model
 * Handles course-based enrollment system (replacing unit-based enrollments)
 */
class CourseEnrollmentsModel extends BaseModel
{
    protected $table = 'tb_course_enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
        'course_id',
        'paid_amount',
        'payment_method',
        'payment_proof',
        'status',
        'approved_at',
        'approved_by',
        'expires_at',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'course_id' => 'required|integer',
        'payment_method' => 'required|in_list[fawry,vodafone_cash,instapay,bank_transfer,credits,free]',
    ];

    /**
     * Get all enrollments for a user with course details
     */
    public function getUserEnrollments(int $userId, ?string $status = null): array
    {
        $builder = $this->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.course_desc, tb_courses.image, tb_courses.course_price, tb_courses.slug')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->where('tb_course_enrollments.user_id', $userId)
            ->orderBy('tb_course_enrollments.created_at', 'DESC');
        
        if ($status) {
            $builder->where('tb_course_enrollments.status', $status);
        }
        
        return $builder->findAll();
    }

    /**
     * Get all enrollments for a course with user details
     */
    public function getCourseEnrollments(int $courseId, ?string $status = null): array
    {
        $builder = $this->select('tb_course_enrollments.*, users.full_name, users.email, users.mobile')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->where('tb_course_enrollments.course_id', $courseId)
            ->orderBy('tb_course_enrollments.created_at', 'DESC');
        
        if ($status) {
            $builder->where('tb_course_enrollments.status', $status);
        }
        
        return $builder->findAll();
    }

    /**
     * Check if user is enrolled in a specific course
     */
    public function isUserEnrolled(int $userId, int $courseId, bool $approvedOnly = true): bool
    {
        $builder = $this->where('user_id', $userId)
            ->where('course_id', $courseId);
        
        if ($approvedOnly) {
            $builder->where('status', 'approved');
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Get pending enrollments for admin approval
     */
    public function getPendingEnrollments(): array
    {
        return $this->select('tb_course_enrollments.*, tb_courses.course_title, users.full_name, users.email, users.mobile')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->where('tb_course_enrollments.status', 'pending')
            ->orderBy('tb_course_enrollments.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Approve an enrollment
     */
    public function approveEnrollment(int $enrollmentId, int $adminId, ?string $expiresAt = null): bool
    {
        $data = [
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
            'approved_by' => $adminId,
        ];
        
        if ($expiresAt) {
            $data['expires_at'] = $expiresAt;
        }
        
        return $this->update($enrollmentId, $data);
    }

    /**
     * Reject an enrollment
     */
    public function rejectEnrollment(int $enrollmentId, ?string $reason = null): bool
    {
        return $this->update($enrollmentId, [
            'status' => 'rejected',
            'notes' => $reason,
        ]);
    }

    /**
     * Create new enrollment for a user
     */
    public function createEnrollment(int $userId, int $courseId, array $paymentData): int|false
    {
        // Check if already enrolled
        if ($this->isUserEnrolled($userId, $courseId, false)) {
            return false;
        }
        
        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'paid_amount' => $paymentData['paid_amount'] ?? 0,
            'payment_method' => $paymentData['payment_method'] ?? 'fawry',
            'payment_proof' => $paymentData['payment_proof'] ?? null,
            'status' => $paymentData['auto_approve'] ?? false ? 'approved' : 'pending',
            'notes' => $paymentData['notes'] ?? null,
        ];
        
        if ($data['status'] === 'approved') {
            $data['approved_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->insert($data) ? $this->getInsertID() : false;
    }

    /**
     * Get enrollment statistics
     */
    public function getEnrollmentStats(): object
    {
        $stats = new \stdClass();
        
        $stats->total = $this->countAll();
        $stats->pending = $this->where('status', 'pending')->countAllResults();
        $stats->approved = $this->where('status', 'approved')->countAllResults();
        $stats->rejected = $this->where('status', 'rejected')->countAllResults();
        $stats->total_revenue = $this->where('status', 'approved')->selectSum('paid_amount')->first()?->paid_amount ?? 0;
        
        return $stats;
    }

    /**
     * Get enrollments for DataTables
     */
    public function getDataTable()
    {
        return $this->select('tb_course_enrollments.*, tb_courses.course_title, users.full_name, users.mobile')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->orderBy('tb_course_enrollments.created_at', 'DESC');
    }
}
