<?php
namespace Modules\Courses\Models;
use App\Models\BaseModel;

class CoursesModel extends BaseModel
{
    protected $table = 'tb_courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_name', 'course_desc', 'image', 'sort', 'price', 'is_free'];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    public function getCourseById($id)
    {
        return $this->db->query("SELECT * FROM tb_courses WHERE id=?", [$id])->getRow();
    }

    public function insertCourse($data, bool $returnID = true)
    {
        $builder = $this->db->table($this->table);
        $builder->insert($data, $returnID);
    }

    public function updateCourse($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->update($id, $data);
    }
}
