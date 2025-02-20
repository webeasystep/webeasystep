<?php
namespace Modules\Subscriptions\Models;
use App\Models\BaseModel;

class SubscriptionsModel extends BaseModel
{
    protected $table = 'tb_subscriptions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'plan_id', 'start_date', 'end_date', 'status'];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    public function getSubscriptionById($id)
    {
        return $this->db->query("SELECT * FROM tb_subscriptions WHERE id=?", [$id])->getRow();
    }

    public function insertSubscription($data, bool $returnID = true)
    {
        $builder = $this->db->table($this->table);
        $builder->insert($data, $returnID);
    }

    public function updateSubscription($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->update($id, $data);
    }

    public function get_users_list(): array
    {
        $builder = $this->db->table('users');
        $builder->select('username, id');
        $builder->where('active', '1');
        $query = $builder->get();
        $list = $query->getResultArray();
        array_unshift($list, ['id' => '', 'username' => '--اختر--']);
        return array_column($list, 'username', 'id');
    }

    public function get_plans_list(): array
    {
        $builder = $this->db->table('tb_plans');
        $builder->select('title, id');
        $builder->where('active', '1');
        $query = $builder->get();
        $list = $query->getResultArray();
        array_unshift($list, ['id' => '', 'title' => '--اختر--']);
        return array_column($list, 'title', 'id');
    }
}
