<?php

namespace Modules\Enrollments\Models;

use App\Models\BaseModel;

class EnrollmentsModel extends BaseModel
{
    protected $table      = 'tb_unit_enrollments';
    protected $primaryKey = 'id';

    /**
     * Updated to use tb_unit_enrollments as primary data source
     */
    protected $allowedFields = [
        'user_id',
        'unit_ids',
        'total_amount',
        'payment_proof',
        'payment_method',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at'
    ];

    protected $useTimestamps = true;
    protected $returnType = 'object';


    /**
     * Create new unit enrollment request
     */
    public function enrollUserInUnits($userId, $unitIds, $totalAmount, $paymentProof = null, $paymentMethod = 'instapay')
    {
        // Validate required fields
        if (empty($userId) || empty($unitIds) || empty($totalAmount)) {
            return false;
        }

        // Validate payment method - only allow instapay and vodafone_cash
        if (!in_array($paymentMethod, ['instapay', 'vodafone_cash'])) {
            $paymentMethod = 'instapay'; // Default to instapay if invalid method provided
        }

        $enrollmentData = [
            'user_id' => $userId,
            'unit_ids' => is_array($unitIds) ? json_encode($unitIds) : $unitIds,
            'total_amount' => $totalAmount,
            'payment_proof' => $paymentProof,
            'payment_method' => $paymentMethod,
            'status' => 'pending'
        ];

        return $this->insert($enrollmentData);
    }


    /**
     * جلب سجل الالتحاق بناءً على المعرف
     */
    public function getEnrollmentById(int $id)
    {
        return $this->find($id);
    }

    /**
     * إدراج سجل جديد
     */
    public function insertEnrollment(array $data, bool $returnID = true)
    {
        return $this->insert($data, $returnID);
    }

    /**
     * تحديث سجل موجود
     */
    public function updateEnrollment(int $id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Get list of available units for enrollment
     */
    public function get_units_list()
    {
        if (class_exists('\Modules\Units\Models\UnitsModel')) {
            $unitsModel = new \Modules\Units\Models\UnitsModel();
            return $unitsModel->where('status', 'active')
                             ->select('id, unit_title as title, unit_desc as description, unit_price as price')
                             ->findAll();
        }
        return [];
    }

    /**
     * Get list of users for admin interface
     */
    public function get_users_list()
    {
        $db = \Config\Database::connect();
        return $db->table('users')
                 ->where('active', 1)
                 ->select('id, username, full_name')
                 ->get()
                 ->getResult();
    }

    /**
     * Get pending enrollment requests for admin
     */
    public function getPendingEnrollments()
    {
        return $this->select('tb_unit_enrollments.*, users.username, users.email')
                   ->join('users', 'users.id = tb_unit_enrollments.user_id')
                   ->where('tb_unit_enrollments.status', 'pending')
                   ->orderBy('tb_unit_enrollments.created_at', 'ASC')
                   ->findAll();
    }

    /**
     * Get enrollment with unit details
     */
    public function getEnrollmentWithUnits($enrollmentId)
    {
        $enrollment = $this->find($enrollmentId);
        if (!$enrollment) {
            return null;
        }

        $unitIds = json_decode($enrollment->unit_ids, true);
        if ($unitIds && class_exists('\Modules\Units\Models\UnitsModel')) {
            $unitsModel = new \Modules\Units\Models\UnitsModel();
            $enrollment->units = $unitsModel->whereIn('id', $unitIds)->findAll();
        } else {
            $enrollment->units = [];
        }

        return $enrollment;
    }
    /**
     * Get user's unit enrollments with unit details
     */
    public function getUserUnitEnrollments(int $userId, string $status = null): array
    {
        $builder = $this->where('user_id', $userId);

        if ($status) {
            $builder->where('status', $status);
        }

        $enrollments = $builder->orderBy('created_at', 'DESC')->findAll();

        // Add unit details to each enrollment
        if ($enrollments && class_exists('\Modules\Units\Models\UnitsModel')) {
            $unitsModel = new \Modules\Units\Models\UnitsModel();

            foreach ($enrollments as &$enrollment) {
                $unitIds = json_decode($enrollment->unit_ids, true);
                if ($unitIds) {
                    $enrollment->units = $unitsModel->whereIn('id', $unitIds)->findAll();
                } else {
                    $enrollment->units = [];
                }
            }
        }

        return $enrollments;
    }

    /**
     * Get unit enrollment statistics
     */
    public function getUnitEnrollmentStats(): array
    {
        $stats = [];

        // Total enrollments
        $stats['total'] = $this->countAll();

        // Enrollments by status
        $stats['pending'] = $this->where('status', 'pending')->countAllResults(false);
        $stats['approved'] = $this->where('status', 'approved')->countAllResults(false);
        $stats['rejected'] = $this->where('status', 'rejected')->countAllResults(false);

        // Recent enrollments (last 30 days)
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $stats['recent'] = $this->where('created_at >=', $thirtyDaysAgo)->countAllResults(false);

        // Total revenue from approved enrollments
        $revenueQuery = $this->select('SUM(total_amount) as total_revenue')
                           ->where('status', 'approved')
                           ->first();
        $stats['total_revenue'] = $revenueQuery->total_revenue ?? 0;

        return $stats;
    }

    /**
     * Approve unit enrollment and grant access
     */
    public function approveEnrollment($enrollmentId, $adminId, $notes = null)
    {
        $enrollment = $this->find($enrollmentId);
        if (!$enrollment) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update enrollment status
            $this->update($enrollmentId, [
                'status' => 'approved',
                'processed_by' => $adminId,
                'processed_at' => date('Y-m-d H:i:s'),
                'admin_notes' => $notes
            ]);

            // Grant access to units
            $unitIds = json_decode($enrollment->unit_ids, true);
            if ($unitIds && class_exists('\Modules\Units\Models\UnitPurchasesModel')) {
                $unitPurchasesModel = new \Modules\Units\Models\UnitPurchasesModel();
                $pricePerUnit = $enrollment->total_amount / count($unitIds);

                foreach ($unitIds as $unitId) {
                    $purchaseData = [
                        'user_id' => $enrollment->user_id,
                        'unit_id' => $unitId,
                        'payment_attachment_id' => $enrollmentId,
                        'price_paid' => $pricePerUnit,
                        'access_granted' => 1,
                        'access_expires_at' => null
                    ];

                    $unitPurchasesModel->insertPurchase($purchaseData);
                }
            }

            $db->transComplete();
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * Reject unit enrollment
     */
    public function rejectEnrollment($enrollmentId, $adminId, $notes = null)
    {
        return $this->update($enrollmentId, [
            'status' => 'rejected',
            'processed_by' => $adminId,
            'processed_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $notes
        ]);
    }

    /**
     * Check if user has access to specific unit
     */
    public function hasUnitAccess($userId, $unitId)
    {
        // Check if user has approved enrollment for this unit
        $enrollments = $this->where('user_id', $userId)
                           ->where('status', 'approved')
                           ->findAll();

        foreach ($enrollments as $enrollment) {
            $unitIds = json_decode($enrollment->unit_ids, true);
            if ($unitIds && in_array($unitId, $unitIds)) {
                return true;
            }
        }

        return false;
    }
}
