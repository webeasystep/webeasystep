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
        'bundle_id',
        'batch_id',
        'paid_amount',
        'coupon_id',
        'coupon_code',
        'coupon_discount_amount',
        'payment_method',
        'payment_proof',
        'refund_proof',
        'status',
        'approved_at',
        'refunded_at',
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
        'payment_method' => 'required|in_list[fawry,vodafone_cash,instapay,bank_transfer,credits,free,paypal,usdt,anb,stc_bank]',
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
        $builder = $this->select('tb_course_enrollments.*, users.full_name, users.email, auth_identities.secret as mobile')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "mobile_number"', 'left')
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
        return $this->select('tb_course_enrollments.*, tb_courses.course_title, users.full_name, users.email, auth_identities.secret as mobile')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "mobile_number"', 'left')
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
     * Mark an enrollment as refunded and revoke course access.
     */
    public function refundEnrollment(int $enrollmentId, ?string $refundProof = null, ?string $notes = null): bool
    {
        $data = [
            'status'      => 'refunded',
            'refunded_at' => date('Y-m-d H:i:s'),
        ];

        if ($refundProof !== null) {
            $data['refund_proof'] = $refundProof;
        }

        if ($notes !== null) {
            $data['notes'] = $notes;
        }

        return $this->update($enrollmentId, $data);
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
            'bundle_id' => $paymentData['bundle_id'] ?? null,
            'batch_id' => $paymentData['batch_id'] ?? null,
            'paid_amount' => $paymentData['paid_amount'] ?? 0,
            'coupon_id' => $paymentData['coupon_id'] ?? null,
            'coupon_code' => $paymentData['coupon_code'] ?? null,
            'coupon_discount_amount' => $paymentData['coupon_discount_amount'] ?? 0,
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
     * Get enrollments for DataTables (groups bundle courses into a single row)
     */
    public function getDataTable()
    {
        return $this->select('
            MIN(tb_course_enrollments.id) as id,
            users.full_name,
            COALESCE(users.mobile, auth_identities.secret) as mobile,
            COUNT(tb_course_enrollments.id) as course_count,
            GROUP_CONCAT(tb_courses.course_title SEPARATOR " || ") as course_title,
            tb_bundles.bundle_title,
            tb_bundles.bundle_price,
            SUM(tb_course_enrollments.paid_amount) as paid_amount,
            MAX(tb_course_enrollments.payment_method) as payment_method,
            MAX(tb_course_enrollments.payment_proof) as payment_proof,
            MIN(tb_course_enrollments.status) as status,
            MAX(tb_course_enrollments.created_at) as created_at,
            tb_course_enrollments.bundle_id,
            tb_course_enrollments.batch_id,
            MIN(tb_course_enrollments.coupon_id) as coupon_id,
            MAX(tb_course_enrollments.coupon_code) as coupon_code,
            SUM(tb_course_enrollments.coupon_discount_amount) as coupon_discount_amount,
            MAX(tb_course_enrollments.approved_at) as approved_at,
            MAX(tb_course_enrollments.approved_by) as approved_by,
            MAX(tb_course_enrollments.expires_at) as expires_at,
            MAX(tb_course_enrollments.notes) as notes,
            MAX(tb_course_enrollments.updated_at) as updated_at,
            MIN(tb_course_enrollments.user_id) as user_id,
            MIN(tb_course_enrollments.course_id) as course_id
        ')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id', 'left')
            ->join('tb_bundles', 'tb_bundles.id = tb_course_enrollments.bundle_id', 'left')
            ->join('users', 'users.id = tb_course_enrollments.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type IN ("mobile_password", "mobile_number")', 'left')
            ->groupBy('
                CASE 
                    WHEN tb_course_enrollments.bundle_id IS NOT NULL AND tb_course_enrollments.batch_id IS NOT NULL 
                    THEN CONCAT("bundle_", tb_course_enrollments.batch_id, "_", tb_course_enrollments.bundle_id)
                    ELSE CONCAT("single_", tb_course_enrollments.id)
                END
            ', false)
            ->orderBy('MAX(tb_course_enrollments.created_at)', 'DESC');
    }
}
