<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Enrollments\Models\EnrollmentsModel;
use Modules\Courses\Models\CoursesModel;

class Enrollments extends BaseController
{
    protected EnrollmentsModel $enrollmentsModel;
    protected CoursesModel $coursesModel;
    // مثلاً إذا أردت التعامل مع رفع الملفات (إثبات الدفع)
    // protected $uploadService;

    public function __construct()
    {
        $this->enrollmentsModel    = new EnrollmentsModel();
        $this->coursesModel     = new CoursesModel();
        // $this->uploadService = ... // إن أردت خدمة رفع ملفات
    }

    public function index(): string
    {
        $data = [
            'title' => lang('Enrollments.Enrollments'),
            'enrollments' => $this->enrollmentsModel
                ->where('enrollment_status', 'completed')
                ->paginate(10),
            'pager' => $this->enrollmentsModel->pager,
        ];

        return view('site/complete_enrollment', $data);
    }

}
