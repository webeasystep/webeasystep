<?php
namespace Modules\Users\Models;

use CodeIgniter\Model;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\GroupModel;
use Faker\Generator;

/**
 * @method User|null first()
 */
class UsersModel extends Model
{
    protected $table          = 'users';
    protected $primaryKey     = 'id';
    protected $returnType     = User::class;
    protected $useSoftDeletes = false;
    protected $allowedFields  = [
        'email', 'full_name', 'address', 'avatar', 'mobile', 'username',
        'parent_name', 'parent_email', 'parent_phone', 'credits',
        'email_verified_at', 'verification_token', 'phone_verified_at', 'phone_verification_code',
        'password_confirm', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_message', 'active', 'force_pass_reset', 'permissions', 'is_deleted',
    ];
    protected $useTimestamps   = true;
/*    protected $validationRules = [
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username'      => 'required|alpha_numeric_punct|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
        'password_confirm' => 'required',
    ];*/
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $afterInsert        = ['addToGroup'];

    /**
     * The id of a group to assign.
     * Set internally by withGroup.
     *
     * @var int|null
     */
    protected $assignGroup;

    /**
     * Get user by ID.
     *
     * @param int $id
     * @return mixed|null
     */
    public function getUserById($id)
    {
        $query = $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->get();

        return $query->getRow();
    }

    /**
     * Logs a password reset attempt for posterity sake.
     */
    public function logResetAttempt(string $email, ?string $token = null, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->db->table('auth_reset_attempts')->insert([
            'email'      => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    private function getColumnNames($usersModel)
    {
        $columnNames = [];
        foreach ($usersModel->getFieldNames() as $columnName) {
            array_push($columnNames, $columnName);
        }
        return $columnNames;
    }
    /**
     * Logs an activation attempt for posterity sake.
     */
    public function logActivationAttempt(?string $token = null, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->db->table('auth_activation_attempts')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Sets the group to assign any users created.
     *
     * @return $this
     */
    public function withGroup(string $groupName)
    {
        $group = $this->db->table('auth_groups')->where('name', $groupName)->get()->getFirstRow();

        $this->assignGroup = $group->id;

        return $this;
    }

    /**
     * Check if user is under 18 years old
     */
    public function isUnder18($userId)
    {
        $user = $this->find($userId);
        if (!$user || !$user->birth_date) {
            return false;
        }
        
        $birthDate = new \DateTime($user->birth_date);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;
        
        return $age < 18;
    }

    /**
     * Get user's credit balance
     */
    public function getCreditBalance($userId)
    {
        $user = $this->find($userId);
        return $user ? $user->credits : 0;
    }

    /**
     * Update user's credit balance
     */
    public function updateCredits($userId, $amount, $description = null)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }
        
        $balanceBefore = $user->credits;
        $balanceAfter = $balanceBefore + $amount;
        
        // Update user credits
        $this->update($userId, ['credits' => $balanceAfter]);
        
        // Log the transaction
        $this->db->table('tb_credit_transactions')->insert([
            'user_id' => $userId,
            'transaction_type' => $amount > 0 ? 'credit_purchase' : 'course_enrollment',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return true;
    }

    /**
     * Generate and store email verification token
     */
    public function generateVerificationToken($userId)
    {
        $token = bin2hex(random_bytes(32));
        $this->update($userId, ['verification_token' => $token]);
        return $token;
    }

    /**
     * Verify email using token
     */
    public function verifyEmail($token)
    {
        $user = $this->where('verification_token', $token)->first();
        if (!$user) {
            return false;
        }
        
        $this->update($user->id, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verification_token' => null,
            'active' => 1
        ]);
        
        return $user;
    }

    /**
     * Generate and store phone verification code
     */
    public function generatePhoneVerificationCode($userId)
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update($userId, ['phone_verification_code' => $code]);
        return $code;
    }

    /**
     * Verify phone using code
     */
    public function verifyPhone($userId, $code)
    {
        $user = $this->find($userId);
        if (!$user || $user->phone_verification_code !== $code) {
            return false;
        }
        
        $this->update($userId, [
            'phone_verified_at' => date('Y-m-d H:i:s'),
            'phone_verification_code' => null
        ]);
        
        return true;
    }

    // Login attempt logging is now handled by Shield's auth_logins table
    // The logLoginAttempt method has been removed as Shield automatically logs all login attempts

    /**
     * Get users who need parent notifications (under 18 with parent email)
     */
    public function getUsersForParentNotification()
    {
        return $this->select('users.*, tb_unit_enrollments.unit_ids, tb_unit_enrollments.total_amount')
            ->join('tb_unit_enrollments', 'tb_unit_enrollments.user_id = users.id')
            ->where('users.parent_email IS NOT NULL')
            ->where('users.birth_date >', date('Y-m-d', strtotime('-18 years')))
            ->where('tb_unit_enrollments.status', 'approved')
            ->findAll();
    }

    /**
     * Clears the group to assign to newly created users.
     *
     * @return $this
     */
    public function clearGroup()
    {
        $this->assignGroup = null;

        return $this;
    }

    /**
     * If a default role is assigned in Config\Auth, will
     * add this user to that group. Will do nothing
     * if the group cannot be found.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected function addToGroup($data)
    {
        if (is_numeric($this->assignGroup)) {
            $groupModel = model(GroupModel::class);
            $groupModel->addUserToGroup($data['id'], $this->assignGroup);
        }

        return $data;
    }

    /**
     * Faked data for Fabricator.
     */
    public function fake(Generator &$faker): User
    {
        return new User([
            'email'    => $faker->email,
            'username' => $faker->userName,
            'password' => bin2hex(random_bytes(16)),
        ]);
    }


}
