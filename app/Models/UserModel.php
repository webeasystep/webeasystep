<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    // Remove custom entity to avoid memory issues
    // protected $returnType = 'App\Entities\User';
    
    protected $allowedFields = [
        'username',
        'status',
        'active',
        'last_active',
        'full_name',
        'mobile',
        'email', // For compatibi
        'avatar',
        'user_type',
        'group_id',
    ];

    public function __construct()
    {
        parent::__construct();
        // Ensure our custom allowedFields are properly set
        $this->allowedFields = array_merge($this->allowedFields, [
            'full_name',
            'mobile',
            'avatar',
            'user_type',
            'group_id',
        ]);
    }

    /**
     * Override insert method to ensure custom fields are saved
     */
    public function insert($data = null, bool $returnID = true)
    {
        log_message('debug', 'UserModel insert called with data: ' . json_encode($data));
        
        // Store original allowedFields
        $originalAllowedFields = $this->allowedFields;
        
        // Merge with Shield's default allowed fields
        $shieldAllowedFields = ['username', 'status', 'status_message', 'active', 'last_active'];
        $this->allowedFields = array_merge($this->allowedFields, $shieldAllowedFields);
        
        log_message('debug', 'UserModel allowedFields before insert: ' . json_encode($this->allowedFields));
        log_message('debug', 'UserModel allowedFields after merge: ' . json_encode($this->allowedFields));
        
        // Call parent insert
        $result = parent::insert($data, $returnID);
        
        // Restore original allowedFields
        $this->allowedFields = $originalAllowedFields;
        
        log_message('debug', 'UserModel insert result: ' . json_encode($result));
        
        return $result;
    }
}