<?php
namespace Modules\Payments\Models;
use App\Models\BaseModel;

class PaymentsModel extends BaseModel
{
    protected $table = 'tb_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'amount', 'payment_method', 'payment_status'];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    public function getPaymentById($id)
    {
        return $this->db->query("SELECT * FROM tb_payments WHERE id=?", [$id])->getRow();
    }

    public function insertPayment($data, bool $returnID = true)
    {
        $builder = $this->db->table($this->table);
        $builder->insert($data, $returnID);
    }

    public function updatePayment($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->update($id, $data);
    }

    public function get_courses_list(): array
    {
        $builder = $this->db->table('tb_courses');
        $builder->select('course_name, id');
        $builder->where('active', '1');
        $query = $builder->get();
        $list = $query->getResultArray();
        array_unshift($list, ['id' => '', 'course_name' => '--اختر--']);
        return array_column($list, 'course_name', 'id');
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
}
