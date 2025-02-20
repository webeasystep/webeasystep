<?php
namespace Modules\Users\Models;

use App\Models\BaseModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\GroupModel;
use Faker\Generator;

/**
 * @method User|null first()
 */
class UsersModel extends BaseModel
{
    protected $table          = 'users';
    protected $primaryKey     = 'id';
    protected $returnType     = User::class;
    protected $useSoftDeletes = false;
    protected $allowedFields  = [
        'email','mobile','gender', 'full_name', 'username','address','job_title_ar','job_title_en','avatar',
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
    public function getUserCategories($userId)
    {
        $userCategories = [];

        // Retrieve the user's selected categories from the database
        $query = $this->db->table('users')
            ->select('category_id')
         //   ->where('id', $userId)
            ->get();
      //  echo $this->db->getLastQuery();
        // Loop through the query results and populate the $userCategories array
        foreach ($query->getResult() as $row) {
            $userCategories[] = $row->category_id;
        }

        return $userCategories;
    }

}
