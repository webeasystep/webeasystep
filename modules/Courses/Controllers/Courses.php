<?php
namespace Modules\Courses\Controllers;
use App\Controllers\BaseController;
use Modules\Courses\Models\CoursesModel;

class Courses extends BaseController
{
    public CoursesModel $coursesModel;

    public function __construct()
    {
        $this->coursesModel = new CoursesModel();
    }

    public function index(): string
    {
        $data = [
            'title' => lang('Courses.Courses'),
            'courses' => $this->coursesModel->where('is_free', 1)->paginate(10),
            'pager' => $this->coursesModel->pager,
        ];

        return view('site/index', $data);
    }
}
