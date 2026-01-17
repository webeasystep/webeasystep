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

    /**
     * Finds a user by credentials, supporting mobile number authentication
     * 
     * @param array $credentials Array containing mobile and/or email credentials
     * @return \CodeIgniter\Shield\Entities\User|null
     */
    public function findByCredentials(array $credentials): ?\CodeIgniter\Shield\Entities\User
    {
        log_message('debug', 'UserModel::findByCredentials - Input credentials: ' . json_encode($credentials));
        
        // Handle mobile authentication
        $mobile = $credentials['mobile'] ?? null;
        unset($credentials['mobile']);
        
        // Handle email authentication (fallback to parent method)
        $email = $credentials['email'] ?? null;
        unset($credentials['email']);

        log_message('debug', 'UserModel::findByCredentials - Mobile: ' . ($mobile ?? 'null') . ', Email: ' . ($email ?? 'null'));

        if ($mobile === null && $email === null && $credentials === []) {
            log_message('debug', 'UserModel::findByCredentials - No valid credentials provided');
            return null;
        }

        // Search by other credentials first (username, etc.)
        foreach ($credentials as $key => $value) {
            $this->where(
                'LOWER(' . $this->db->protectIdentifiers($this->table . ".{$key}") . ')',
                strtolower($value)
            );
        }

        // Handle mobile authentication
        if ($mobile !== null) {
            log_message('debug', 'UserModel::findByCredentials - Searching for mobile: ' . $mobile);
            
            /** @var array<string, int|string|null>|null $data */
            $data = $this->select(
                sprintf('%1$s.*, %2$s.secret as mobile, %2$s.secret2 as password_hash', $this->table, $this->tables['identities'])
            )
                ->join($this->tables['identities'], sprintf('%1$s.user_id = %2$s.id', $this->tables['identities'], $this->table))
                ->where($this->tables['identities'] . '.type', 'mobile_password')
                ->where(
                    'LOWER(' . $this->db->protectIdentifiers($this->tables['identities'] . '.secret') . ')',
                    strtolower($mobile)
                )
                ->asArray()
                ->first();

            log_message('debug', 'UserModel::findByCredentials - Query result: ' . json_encode($data));

            if ($data !== null) {
                $mobile_number = $data['mobile'];
                unset($data['mobile']);
                $password_hash = $data['password_hash'];
                unset($data['password_hash']);

                log_message('debug', 'UserModel::findByCredentials - Found user with mobile: ' . $mobile_number);
                log_message('debug', 'UserModel::findByCredentials - Password hash: ' . substr($password_hash, 0, 20) . '...');

                // Ensure email is not null to avoid Shield User entity error
                if (!isset($data['email']) || $data['email'] === null) {
                    $data['email'] = '';
                }

                $user = new $this->returnType($data);
                $user->mobile = $mobile_number;
                $user->password_hash = $password_hash;
                $user->syncOriginal();

                log_message('debug', 'UserModel::findByCredentials - User entity created successfully');
                return $user;
            } else {
                log_message('debug', 'UserModel::findByCredentials - No user found with mobile: ' . $mobile);
            }
        }

        // Handle email authentication (fallback to parent method)
        if ($email !== null) {
            // Reset the query builder for email search
            $this->resetQuery();
            
            // Search by other credentials again for email
            foreach ($credentials as $key => $value) {
                $this->where(
                    'LOWER(' . $this->db->protectIdentifiers($this->table . ".{$key}") . ')',
                    strtolower($value)
                );
            }
            
            /** @var array<string, int|string|null>|null $data */
            $data = $this->select(
                sprintf('%1$s.*, %2$s.secret as email, %2$s.secret2 as password_hash', $this->table, $this->tables['identities'])
            )
                ->join($this->tables['identities'], sprintf('%1$s.user_id = %2$s.id', $this->tables['identities'], $this->table))
                ->where($this->tables['identities'] . '.type', \CodeIgniter\Shield\Authentication\Authenticators\Session::ID_TYPE_EMAIL_PASSWORD)
                ->where(
                    'LOWER(' . $this->db->protectIdentifiers($this->tables['identities'] . '.secret') . ')',
                    strtolower($email)
                )
                ->asArray()
                ->first();

            if ($data !== null) {
                $email_address = $data['email'];
                unset($data['email']);
                $password_hash = $data['password_hash'];
                unset($data['password_hash']);

                $user = new $this->returnType($data);
                $user->email = $email_address;
                $user->password_hash = $password_hash;
                $user->syncOriginal();

                return $user;
            }
        }

        // If no mobile or email, try to find by other credentials
        if ($credentials !== []) {
            return $this->first();
        }

        return null;
    }
}