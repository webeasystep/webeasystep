<?php
namespace Modules\CoursesSections\Controllers;
use App\Controllers\BaseController;
use Modules\CoursesSections\Models\CoursesSectionsModel;

class CoursesSections extends BaseController
{
    public CoursesSectionsModel $coursesSectionsModel;

    public function __construct()
    {
        $this->coursesSectionsModel = new CoursesSectionsModel();
    }

    public function index(): string
    {
        $data = [
            'title' => lang('CoursesSections.Sections'),
            'sections' => $this->coursesSectionsModel->orderBy('sort')->paginate(10),
            'pager' => $this->coursesSectionsModel->pager,
        ];

        return view('Modules\CoursesSections\Views\Site\index', $data);
    }
}
