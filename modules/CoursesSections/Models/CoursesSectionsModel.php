<?php
namespace Modules\CoursesSections\Models;
use App\Models\BaseModel;

class CoursesSectionsModel extends BaseModel
{
    protected $table = 'tb_courses_sections';
    protected $primaryKey = 'id';
    protected $allowedFields = ['section_name', 'section_desc', 'sort'];
    protected $useTimestamps = true;
    protected $returnType = 'object';
}
