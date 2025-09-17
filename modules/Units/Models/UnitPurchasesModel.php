<?php
namespace Modules\Units\Models;
use App\Models\BaseModel;

class UnitPurchasesModel extends BaseModel
{
    protected $table = 'tb_unit_purchases';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'unit_id', 'payment_attachment_id', 'price_paid',
        'access_granted', 'access_expires_at'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    /**
     * Get purchase details by ID
     */
    public function getPurchaseById($id)
    {
        return $this->db->query("SELECT up.*, u.username, u.email, un.unit_name 
                                FROM tb_unit_purchases up 
                                LEFT JOIN users u ON up.user_id = u.id 
                                LEFT JOIN tb_units un ON up.unit_id = un.id 
                                WHERE up.id=?", [$id])->getRow();
    }

    /**
     * Get all purchases with user and unit details
     */
    public function detailCustomer($id = null)
    {
        $builder = $this->builder($this->table)
            ->select('tb_unit_purchases.*, users.username, users.email, tb_units.unit_name')
            ->join('users', 'users.id = tb_unit_purchases.user_id', 'left')
            ->join('tb_units', 'tb_units.id = tb_unit_purchases.unit_id', 'left');

        if (empty($id)) {
            return $builder->orderBy('tb_unit_purchases.created_at', 'DESC')->get()->getResult();
        } else {
            return $builder->where('tb_unit_purchases.id', $id)->get(1)->getRow();
        }
    }

    /**
     * Check if user has access to a specific unit
     */
    public function hasUnitAccess($userId, $unitId)
    {
        $purchase = $this->where('user_id', $userId)
                        ->where('unit_id', $unitId)
                        ->where('access_granted', 1)
                        ->first();

        if (!$purchase) {
            return false;
        }

        // Check if access has expired
        if ($purchase->access_expires_at && strtotime($purchase->access_expires_at) < time()) {
            return false;
        }

        return true;
    }

    /**
     * Get user's purchased units
     */
    public function getUserPurchases($userId, $activeOnly = true)
    {
        $builder = $this->select('tb_unit_purchases.*, tb_units.unit_name, tb_units.unit_desc, tb_units.video_id, tb_courses.course_title')
                        ->join('tb_units', 'tb_units.id = tb_unit_purchases.unit_id')
                        ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                        ->where('tb_unit_purchases.user_id', $userId);

        if ($activeOnly) {
            $builder->where('tb_unit_purchases.access_granted', 1)
                   ->groupStart()
                   ->where('tb_unit_purchases.access_expires_at IS NULL')
                   ->orWhere('tb_unit_purchases.access_expires_at >', date('Y-m-d H:i:s'))
                   ->groupEnd();
        }

        return $builder->orderBy('tb_unit_purchases.created_at', 'DESC')->findAll();
    }

    /**
     * Get units purchased by payment attachment
     */
    public function getUnitsByPayment($paymentId)
    {
        return $this->select('tb_unit_purchases.*, tb_units.unit_name, tb_units.unit_desc')
                   ->join('tb_units', 'tb_units.id = tb_unit_purchases.unit_id')
                   ->where('tb_unit_purchases.payment_attachment_id', $paymentId)
                   ->findAll();
    }

    /**
     * Custom insert method
     */
    public function insertPurchase($data, bool $returnID = true)
    {
        $builder = $this->db->table($this->table);
        $builder->insert($data);

        if ($returnID) {
            return $this->db->insertID();
        }
        return true;
    }

    /**
     * Custom update method
     */
    public function updatePurchase($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id', $id);
        return $builder->update($data);
    }

    /**
     * Grant access to unit
     */
    public function grantAccess($userId, $unitId, $expiresAt = null)
    {
        $data = [
            'access_granted' => 1,
            'access_expires_at' => $expiresAt
        ];

        return $this->where('user_id', $userId)
                   ->where('unit_id', $unitId)
                   ->set($data)
                   ->update();
    }

    /**
     * Revoke access to unit
     */
    public function revokeAccess($userId, $unitId)
    {
        return $this->where('user_id', $userId)
                   ->where('unit_id', $unitId)
                   ->set('access_granted', 0)
                   ->update();
    }

    /**
     * Get purchase statistics
     */
    public function getPurchaseStats()
    {
        $stats = [];

        $stats['total_purchases'] = $this->countAll();
        $stats['active_purchases'] = $this->where('access_granted', 1)->countAllResults(false);

        // Total revenue
        $revenueQuery = $this->select('SUM(price_paid) as total_revenue')
                           ->where('access_granted', 1)
                           ->first();
        $stats['total_revenue'] = $revenueQuery->total_revenue ?? 0;

        // Most purchased units
        $popularUnits = $this->select('unit_id, COUNT(*) as purchase_count, tb_units.unit_name')
                           ->join('tb_units', 'tb_units.id = tb_unit_purchases.unit_id')
                           ->where('access_granted', 1)
                           ->groupBy('unit_id')
                           ->orderBy('purchase_count', 'DESC')
                           ->limit(5)
                           ->findAll();
        $stats['popular_units'] = $popularUnits;

        return $stats;
    }

    /**
     * Get expired accesses
     */
    public function getExpiredAccesses()
    {
        return $this->select('tb_unit_purchases.*, users.username, tb_units.unit_name')
                   ->join('users', 'users.id = tb_unit_purchases.user_id')
                   ->join('tb_units', 'tb_units.id = tb_unit_purchases.unit_id')
                   ->where('tb_unit_purchases.access_granted', 1)
                   ->where('tb_unit_purchases.access_expires_at IS NOT NULL')
                   ->where('tb_unit_purchases.access_expires_at <', date('Y-m-d H:i:s'))
                   ->findAll();
    }

    /**
     * Clean up expired accesses
     */
    public function cleanupExpiredAccesses()
    {
        return $this->where('access_granted', 1)
                   ->where('access_expires_at IS NOT NULL')
                   ->where('access_expires_at <', date('Y-m-d H:i:s'))
                   ->set('access_granted', 0)
                   ->update();
    }

    /**
     * Get user's unit access summary
     */
    public function getUserAccessSummary($userId)
    {
        $summary = [];

        // Total units purchased
        $summary['total_purchased'] = $this->where('user_id', $userId)->countAllResults(false);

        // Active units
        $summary['active_units'] = $this->where('user_id', $userId)
                                       ->where('access_granted', 1)
                                       ->groupStart()
                                       ->where('access_expires_at IS NULL')
                                       ->orWhere('access_expires_at >', date('Y-m-d H:i:s'))
                                       ->groupEnd()
                                       ->countAllResults(false);

        // Expired units
        $summary['expired_units'] = $this->where('user_id', $userId)
                                        ->where('access_granted', 1)
                                        ->where('access_expires_at IS NOT NULL')
                                        ->where('access_expires_at <', date('Y-m-d H:i:s'))
                                        ->countAllResults(false);

        // Total spent
        $spentQuery = $this->select('SUM(price_paid) as total_spent')
                          ->where('user_id', $userId)
                          ->where('access_granted', 1)
                          ->first();
        $summary['total_spent'] = $spentQuery->total_spent ?? 0;

        return $summary;
    }
}
