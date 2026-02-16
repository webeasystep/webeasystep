<?php

declare(strict_types=1);

namespace  Modules\Groups\Models;

use App\Models\BaseModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\CheckQueryReturnTrait;
use Modules\Permissions\Models\PermissionsModel;

class GroupsModel extends BaseModel
{
    use CheckQueryReturnTrait;

    protected $table          = 'auth_groups';
    protected $primaryKey     = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields  = [
        'group_name',
        'title',
        'description',
    ];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getForUser(User $user): array
    {
        $rows = $this->builder()
            ->select('group')
            ->where('user_id', $user->id)
            ->get()
            ->getResultArray();

        return array_column($rows, 'group');
    }

    /**
     * @param int|string $userId
     */
    public function deleteAll($userId): void
    {
        $return = $this->builder()
            ->where('user_id', $userId)
            ->delete();

        $this->checkQueryReturn($return);
    }

    /**
     * @param int|string $userId
     * @param mixed      $cache
     */
    public function deleteNotIn($userId, $cache): void
    {
        $return = $this->builder()
            ->where('user_id', $userId)
            ->whereNotIn('group', $cache)
            ->delete();

        $this->checkQueryReturn($return);
    }
    public function getSelectedPermissions($groupId = NULL): array
    {
        $SelectedPermissions = [];

        // Retrieve the user's selected categories from the database
        $query = $this->db->table('auth_permissions_users')
            ->select('permission')
             ->where('group_id', $groupId)
            ->get();
        //  echo $this->db->getLastQuery();
        // Loop through the query results and populate the $userCategories array
        foreach ($query->getResult() as $row) {
            $SelectedPermissions[] = $row->permission;
        }

        return $SelectedPermissions;
    }

    public function getGroupPermissions($groupId)
    {
        return $this->db->table('auth_permissions_users')
            ->where('group_id', $groupId)
            ->get()
            ->getResult();
    }

    public function insertGroupPermissions($groupId, $selectedPermissions)
    {
        // Delete old permissions related to the group
        $this->db->table('auth_permissions_users')
            ->where('group_id', $groupId)
            ->delete();

        $permissionsData = [];

        foreach ($selectedPermissions as $permission) {
            $permissionsData[] = [
                'group_id' => $groupId,
                'permission' => $permission,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        // Insert new permissions
        $this->db->table('auth_permissions_users')->insertBatch($permissionsData);
    }


}
