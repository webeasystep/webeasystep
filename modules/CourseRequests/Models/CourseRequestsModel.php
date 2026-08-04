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
}
