<?php
namespace Modules\Courses\Models;

use App\Models\BaseModel;
use CodeIgniter\Database\BaseBuilder;

class CoursesModel extends BaseModel
{
    protected $table         = 'tb_courses';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['course_name', 'course_desc', 'image', 'sort', 'price', 'is_free', 'active', 'course_structure', 'slug'];
    protected $useTimestamps = true;
    protected $returnType     = 'object';

    public function getCourseById(int $id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Optionally fetch a course by 'slug' column
     */
    public function getCourseBySlug(string $slug)
    {
        return $this->where('slug', $slug)
            ->where('active', 1)
            ->first();
    }

    public function insertCourse(array $data)
    {
        return $this->insert($data); // returns ID if $returnID = true
    }

    public function updateCourse(int $id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * If you have an enrollment table, e.g. tb_enrollments(user_id, course_id)
     * and you want to fetch all courses for a given user:
     */
    public function getAllUserCourses(int $userId)
    {
        $builder = $this->db->table('tb_enrollments');
        $builder->select('tb_courses.*');
        $builder->join('tb_courses', 'tb_courses.id = tb_enrollments.course_id');
        $builder->where('tb_enrollments.user_id', $userId);
        $builder->where('tb_courses.active', 1); // only active courses
        return $builder->get()->getResult();
    }
}
