<?php

namespace Modules\Enrollments\Models;

use App\Models\BaseModel;

class UnitEnrollmentsModel extends BaseModel
{
    protected $table = 'tb_unit_enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'unit_id', // Single unit ID instead of JSON array
        'total_amount',
        'payment_proof',
        'payment_method',
        'status', // pending, approved, rejected
        'admin_notes',
        'processed_by',
        'processed_at'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    /**
     * Create new unit enrollment request for a single unit
     */
    public function createUnitEnrollment(array $data)
    {
        // Validate required fields
        if (empty($data['user_id']) || empty($data['unit_id']) || !isset($data['total_amount'])) {
            return false;
        }

        $enrollmentData = [
            'user_id' => $data['user_id'],
            'unit_id' => (int) $data['unit_id'],
            'total_amount' => $data['total_amount'],
            'payment_proof' => $data['payment_proof'] ?? null,
            'payment_method' => $data['payment_method'] ?? 'instapay',
            'status' => $data['status'] ?? 'pending',
        ];

        return $this->insert($enrollmentData);
    }

    /**
     * Create multiple unit enrollments for multiple units (batch creation)
     */
    public function createMultipleUnitEnrollments(array $data)
    {
        // Validate required fields
        if (empty($data['user_id']) || empty($data['unit_ids']) || !isset($data['total_amount'])) {
            return false;
        }

        $unitIds = is_array($data['unit_ids']) ? $data['unit_ids'] : json_decode($data['unit_ids'], true);
        if (!is_array($unitIds) || empty($unitIds)) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $totalAmount = (float) $data['total_amount'];
            $unitCount = count($unitIds);
            $amountPerUnit = $unitCount > 0 ? $totalAmount / $unitCount : 0;

            $enrollmentIds = [];

            foreach ($unitIds as $unitId) {
                $enrollmentData = [
                    'user_id' => $data['user_id'],
                    'unit_id' => (int) $unitId,
                    'total_amount' => $amountPerUnit,
                    'payment_proof' => $data['payment_proof'] ?? null,
                    'payment_method' => $data['payment_method'] ?? 'instapay',
                    'status' => $data['status'] ?? 'pending'
                ];

                $enrollmentId = $this->insert($enrollmentData);
                if ($enrollmentId) {
                    $enrollmentIds[] = $enrollmentId;
                } else {
                    throw new \Exception("Failed to create enrollment for unit ID: {$unitId}");
                }
            }

            $db->transComplete();
            return $db->transStatus() ? $enrollmentIds : false;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Failed to create multiple unit enrollments: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user's unit enrollment requests
     */
    public function getUserEnrollments($userId, $status = null)
    {
        $builder = $this->where('user_id', $userId);

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
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
                        'payment_attachment_id' => $enrollmentId, // Reference to this enrollment
                        'price_paid' => $pricePerUnit,
                        'access_granted' => 1,
                        'access_expires_at' => null // Lifetime access
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
     * Get enrollment with unit details
     */
    public function getEnrollmentWithUnits($enrollmentId)
    {
        return $this->select('tb_unit_enrollments.*, tb_units.unit_name, tb_units.price, tb_courses.course_name')
                    ->join('tb_units', 'tb_units.id = tb_unit_enrollments.unit_id')
                    ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                    ->where('tb_unit_enrollments.id', $enrollmentId)
                    ->first();
    }

    /**
     * Get all enrollments for a user with unit details
     */
    public function getUserEnrollmentsWithUnits($userId)
    {
        return $this->select('tb_unit_enrollments.*, tb_units.unit_name, tb_units.price, tb_courses.course_name, tb_courses.id as course_id')
                    ->join('tb_units', 'tb_units.id = tb_unit_enrollments.unit_id')
                    ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                    ->where('tb_unit_enrollments.user_id', $userId)
                    ->orderBy('tb_unit_enrollments.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get user's approved unit enrollments
     */
    public function getUserApprovedEnrollments($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('status', 'approved')
                    ->findAll();
    }

    /**
     * Get all unit IDs that a user has access to
     */
    public function getUserAccessibleUnitIds($userId)
    {
        $enrollments = $this->select('unit_id')
                           ->where('user_id', $userId)
                           ->where('status', 'approved')
                           ->findAll();

        return array_column($enrollments, 'unit_id');
    }

    /**
     * Check if user has access to a specific unit
     */
    public function hasUnitAccess($userId, $unitId)
    {
        $enrollment = $this->where('user_id', $userId)
                          ->where('unit_id', $unitId)
                          ->where('status', 'approved')
                          ->first();

        return !empty($enrollment);
    }

    /**
     * Get enrollment statistics
     */
    public function getEnrollmentStats()
    {
        $stats = [];

        $stats['total'] = $this->countAll();
        $stats['pending'] = $this->where('status', 'pending')->countAllResults(false);
        $stats['approved'] = $this->where('status', 'approved')->countAllResults(false);
        $stats['rejected'] = $this->where('status', 'rejected')->countAllResults(false);

        // Total revenue from approved enrollments
        $revenueQuery = $this->select('SUM(total_amount) as total_revenue')
                           ->where('status', 'approved')
                           ->first();
        $stats['total_revenue'] = $revenueQuery->total_revenue ?? 0;

        return $stats;
    }

    /**
     * Get list of available units for enrollment
     */
    public function get_units_list()
    {
        if (class_exists('\Modules\Units\Models\UnitsModel')) {
            $unitsModel = new \Modules\Units\Models\UnitsModel();
            $units = $unitsModel->where('active', 1)
                               ->select('id, unit_name, unit_desc, price')
                               ->findAll();

            $unitsList = [];
            foreach ($units as $unit) {
                $unitsList[$unit->id] = $unit->unit_name . ' - $' . number_format($unit->price, 2);
            }
            return $unitsList;
        }
        return [];
    }

    /**
     * Get list of users for enrollment
     */
    public function get_users_list()
    {
        if (class_exists('\Modules\Users\Models\UsersModel')) {
            $usersModel = new \Modules\Users\Models\UsersModel();
            $users = $usersModel->select('id, username, email')
                               ->where('active', 1)
                               ->findAll();

            $usersList = [];
            foreach ($users as $user) {
                $usersList[$user->id] = $user->username . ' (' . $user->email . ')';
            }
            return $usersList;
        }
        return [];
    }

    /**
     * Check for duplicate enrollments for a specific unit
     */
    public function checkDuplicateEnrollments($userId, $unitId)
    {
        return $this->where('user_id', $userId)
                    ->where('unit_id', $unitId)
                    ->whereIn('status', ['pending', 'approved'])
                    ->first();
    }

    /**
     * Check for duplicate enrollments for multiple units
     */
    public function checkDuplicateEnrollmentsForUnits($userId, $unitIds)
    {
        if (!is_array($unitIds) || empty($unitIds)) {
            return [];
        }

        return $this->where('user_id', $userId)
                    ->whereIn('unit_id', $unitIds)
                    ->whereIn('status', ['pending', 'approved'])
                    ->findAll();
    }
}
