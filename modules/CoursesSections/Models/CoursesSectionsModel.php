<?php
namespace Modules\Plans\Models;
use App\Models\BaseModel;

class PlansModel extends BaseModel
{
    protected $table = 'tb_plans';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'price', 'duration_days'];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    public function getPlanById($id)
    {
        return $this->db->query("SELECT * FROM tb_plans WHERE id=?", [$id])->getRow();
    }

    public function insertPlan($data, bool $returnID = true)
    {
        $builder = $this->db->table($this->table);
        $builder->insert($data, $returnID);
    }

    public function updatePlan($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->update($id, $data);
    }
}
