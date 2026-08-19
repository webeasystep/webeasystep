<?php

namespace Modules\CourseRequests\Models;

use CodeIgniter\Model;

class CourseRequestsModel extends Model
{
    protected $table = 'tb_course_requests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'course_name_code',
        'college_id',
        'department_id',
        'notify_me',
        'contact_info',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get DataTable query builder with college and department joins
     */
    public function getDataTable()
    {
        return $this->select('tb_course_requests.id, tb_course_requests.course_name_code, tb_colleges.college_name_ar as college_name, tb_departments.department_name_ar as department_name, tb_course_requests.contact_info, tb_course_requests.notify_me, tb_course_requests.status, tb_course_requests.created_at')
            ->join('tb_colleges', 'tb_colleges.id = tb_course_requests.college_id', 'left')
            ->join('tb_departments', 'tb_departments.id = tb_course_requests.department_id', 'left')
            ->orderBy('tb_course_requests.id', 'desc');
    }

    /**
     * Get single request with college and department info
     */
    public function getRequestDetails($id)
    {
        return $this->select('tb_course_requests.*, tb_colleges.college_name_ar as college_name, tb_departments.department_name_ar as department_name')
            ->join('tb_colleges', 'tb_colleges.id = tb_course_requests.college_id', 'left')
            ->join('tb_departments', 'tb_departments.id = tb_course_requests.department_id', 'left')
            ->where('tb_course_requests.id', $id)
            ->first();
    }

    /**
     * Get quick statistics for admin dashboard/index
     */
    public function getStatistics(): array
    {
        $total = $this->countAllResults(false);
        $pending = $this->where('status', 'pending')->orWhere('status IS NULL')->countAllResults(false);
        $completed = $this->where('status', 'completed')->countAllResults(false);
        $notify = $this->where('notify_me', 1)->countAllResults(false);

        return [
            'total'     => $total,
            'pending'   => $pending,
            'completed' => $completed,
            'notify'    => $notify,
        ];
    }
}
