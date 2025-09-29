<?php

namespace Modules\Enrollments\Models;

use CodeIgniter\Model;

/**
 * UnitPurchasesModel
 * 
 * Handles unit purchases and access management
 */
class UnitPurchasesModel extends Model
{
    protected $table = 'tb_unit_purchases';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'unit_id', 
        'enrollment_id',
        'amount',
        'purchase_date',
        'status',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'unit_id' => 'required|integer',
        'enrollment_id' => 'required|integer',
        'amount' => 'required|decimal',
        'status' => 'required|in_list[active,inactive,expired]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'unit_id' => [
            'required' => 'Unit ID is required',
            'integer' => 'Unit ID must be an integer'
        ],
        'enrollment_id' => [
            'required' => 'Enrollment ID is required',
            'integer' => 'Enrollment ID must be an integer'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid decimal number'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: active, inactive, expired'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Create a new unit purchase record
     * 
     * @param array $data Purchase data
     * @return int|false Purchase ID on success, false on failure
     */
    public function createPurchase($data)
    {
        try {
            // Set default values
            $data['purchase_date'] = $data['purchase_date'] ?? date('Y-m-d H:i:s');
            $data['status'] = $data['status'] ?? 'active';
            
            // Insert the purchase record
            $result = $this->insert($data);
            
            if ($result) {
                log_message('debug', 'UnitPurchasesModel::createPurchase - Purchase created successfully with ID: ' . $result);
                return $result;
            } else {
                log_message('error', 'UnitPurchasesModel::createPurchase - Failed to create purchase: ' . json_encode($this->errors()));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'UnitPurchasesModel::createPurchase - Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user purchases for a specific unit
     * 
     * @param int $userId User ID
     * @param int $unitId Unit ID
     * @return array
     */
    public function getUserUnitPurchases($userId, $unitId)
    {
        return $this->where('user_id', $userId)
                   ->where('unit_id', $unitId)
                   ->where('status', 'active')
                   ->findAll();
    }

    /**
     * Check if user has active purchase for a unit
     * 
     * @param int $userId User ID
     * @param int $unitId Unit ID
     * @return bool
     */
    public function hasActivePurchase($userId, $unitId)
    {
        $purchase = $this->where('user_id', $userId)
                        ->where('unit_id', $unitId)
                        ->where('status', 'active')
                        ->first();
        
        return !empty($purchase);
    }

    /**
     * Get all purchases for a user
     * 
     * @param int $userId User ID
     * @return array
     */
    public function getUserPurchases($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Deactivate a purchase
     * 
     * @param int $purchaseId Purchase ID
     * @return bool
     */
    public function deactivatePurchase($purchaseId)
    {
        return $this->update($purchaseId, ['status' => 'inactive']);
    }

    /**
     * Activate a purchase
     * 
     * @param int $purchaseId Purchase ID
     * @return bool
     */
    public function activatePurchase($purchaseId)
    {
        return $this->update($purchaseId, ['status' => 'active']);
    }
}