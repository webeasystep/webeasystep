<?php

namespace Modules\Enrollments\Models;

use App\Models\BaseModel;

class UnitEnrollmentsModel extends BaseModel
{
    protected $table = 'tb_unit_enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'unit_ids', // JSON array of unit IDs
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
     * Create new unit enrollment request
     */
    public function createUnitEnrollment(array $data)
    {
        // Validate required fields
        if (empty($data['user_id']) || empty($data['unit_ids']) || empty($data['total_amount'])) {
            return false;
        }

        $enrollmentData = [
            'user_id' => $data['user_id'],
            'unit_ids' => is_array($data['unit_ids']) ? json_encode($data['unit_ids']) : $data['unit_ids'],
            'total_amount' => $data['total_amount'],
            'payment_proof' => $data['payment_proof'] ?? null,
            'payment_method' => $data['payment_method'] ?? 'bank_transfer',
            'status' => 'pending'
        ];

        return $this->insert($enrollmentData);
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
}