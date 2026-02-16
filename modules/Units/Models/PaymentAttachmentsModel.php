<?php
namespace Modules\Units\Models;
use App\Models\BaseModel;

class PaymentAttachmentsModel extends BaseModel
{
    protected $table = 'tb_payment_attachments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'unit_ids', 'total_price', 'payment_attachment', 
        'payment_method', 'status', 'admin_notes', 'approved_by', 'approved_at'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    /**
     * Get payment attachment details by ID
     */
    public function getPaymentById($id)
    {
        return $this->db->query("SELECT pa.*, u.username, u.email FROM tb_payment_attachments pa 
                                LEFT JOIN users u ON pa.user_id = u.id 
                                WHERE pa.id=?", [$id])->getRow();
    }

    /**
     * Get all payment attachments with user details
     */
    public function detailCustomer($id = null)
    {
        $builder = $this->builder($this->table)
            ->select('tb_payment_attachments.*, users.username, users.email')
            ->join('users', 'users.id = tb_payment_attachments.user_id', 'left');
        
        if (empty($id)) {
            return $builder->orderBy('tb_payment_attachments.created_at', 'DESC')->get()->getResult();
        } else {
            return $builder->where('tb_payment_attachments.id', $id)->get(1)->getRow();
        }
    }

    /**
     * Get pending payment attachments
     */
    public function getPendingPayments()
    {
        return $this->select('tb_payment_attachments.*, users.username, users.email')
                   ->join('users', 'users.id = tb_payment_attachments.user_id', 'left')
                   ->where('tb_payment_attachments.status', 'pending')
                   ->orderBy('tb_payment_attachments.created_at', 'ASC')
                   ->findAll();
    }

    /**
     * Get user's payment history
     */
    public function getUserPayments($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Custom insert method
     */
    public function insertPayment($data, bool $returnID = true)
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
    public function updatePayment($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id', $id);
        return $builder->update($data);
    }

    /**
     * Approve payment and grant unit access
     */
    public function approvePayment($id, $adminId, $notes = null)
    {
        $payment = $this->find($id);
        if (!$payment) {
            return false;
        }

        // Start transaction
        $this->db->transStart();

        // Update payment status
        $updateData = [
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $notes
        ];
        
        $this->update($id, $updateData);

        // Grant access to units
        $unitIds = json_decode($payment->unit_ids, true);
        if ($unitIds) {
            $unitPurchasesModel = new UnitPurchasesModel();
            $pricePerUnit = $payment->total_price / count($unitIds);
            
            foreach ($unitIds as $unitId) {
                $purchaseData = [
                    'user_id' => $payment->user_id,
                    'unit_id' => $unitId,
                    'payment_attachment_id' => $id,
                    'price_paid' => $pricePerUnit,
                    'access_granted' => 1,
                    'access_expires_at' => null // Lifetime access
                ];
                
                $unitPurchasesModel->insertPurchase($purchaseData);
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Reject payment
     */
    public function rejectPayment($id, $adminId, $notes = null)
    {
        $updateData = [
            'status' => 'rejected',
            'approved_by' => $adminId,
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $notes
        ];
        
        return $this->update($id, $updateData);
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats()
    {
        $stats = [];
        
        $stats['total'] = $this->countAll();
        $stats['pending'] = $this->where('status', 'pending')->countAllResults(false);
        $stats['approved'] = $this->where('status', 'approved')->countAllResults(false);
        $stats['rejected'] = $this->where('status', 'rejected')->countAllResults(false);
        
        // Total revenue from approved payments
        $revenueQuery = $this->select('SUM(total_price) as total_revenue')
                           ->where('status', 'approved')
                           ->first();
        $stats['total_revenue'] = $revenueQuery->total_revenue ?? 0;
        
        return $stats;
    }

    /**
     * Get payments for DataTable
     */
    public function getPaymentsForDataTable($filters = [])
    {
        $builder = $this->select('tb_payment_attachments.*, users.username, users.email')
                       ->join('users', 'users.id = tb_payment_attachments.user_id', 'left');
        
        // Apply filters
        if (!empty($filters['status'])) {
            $builder->where('tb_payment_attachments.status', $filters['status']);
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('tb_payment_attachments.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('tb_payment_attachments.created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('tb_payment_attachments.created_at <=', $filters['date_to']);
        }
        
        return $builder->orderBy('tb_payment_attachments.created_at', 'DESC')
                      ->findAll();
    }
}