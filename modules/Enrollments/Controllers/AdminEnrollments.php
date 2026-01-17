<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Enrollments\Models\EnrollmentsModel;
use Modules\Enrollments\Models\CourseEnrollmentsModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Users\Models\UsersModel;

class AdminEnrollments extends BaseController
{
    protected EnrollmentsModel $enrollments;
    protected CourseEnrollmentsModel $courseEnrollments;
    protected CoursesModel $coursesModel;
    protected UsersModel $usersModel;
    protected string $table = 'tb_course_enrollments';

    public function __construct()
    {
        $this->enrollments = new EnrollmentsModel();
        $this->courseEnrollments = new CourseEnrollmentsModel();
        $this->coursesModel = new CoursesModel();
        $this->usersModel = new UsersModel();
    }

    /**
     * Show a listing of all course enrollments with DataTables
     */
    public function index()
    {
        // Redirect to course enrollments as primary view
        return redirect()->to(ADMIN_URL . 'enrollments/courses');
    }

    /**
     * Display course enrollment requests
     */
    public function courseEnrollments()
    {
        $data['title'] = 'طلبات شراء الدورات';

        if ($this->request->isAJAX()) {
            $builder = $this->courseEnrollments->getDataTable()->builder();

            DtTable::hideColumns(['id', 'user_id', 'course_id']);
            DtTable::searchableColumns(['full_name', 'mobile', 'course_title', 'status']);
            DtTable::orderableColumns(['full_name', 'course_title', 'paid_amount', 'status', 'created_at']);
            DtTable::setShowColumns('full_name,mobile,course_title,paid_amount,payment_method,status,created_at');

            $output = DtTable::tableRender($builder, false);
            return $this->response->setJSON($output);
        }

        return view('Admin/course_enrollments', $data);
    }

    /**
     * View course enrollment details
     */
    public function showCourseEnrollment($id)
    {
        $enrollment = $this->courseEnrollments
            ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.course_price, users.full_name, users.email, users.mobile')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->find($id);

        if (!$enrollment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('طلب الشراء غير موجود');
        }

        $data = [
            'title' => 'تفاصيل طلب شراء الدورة',
            'enrollment' => $enrollment
        ];

        return view('Admin/course_enrollment_details', $data);
    }

    /**
     * Approve course enrollment request
     */
    public function approveCourseEnrollment($id)
    {
        $adminId = auth()->user()->id;
        $notes = $this->request->getPost('admin_notes');
        $expiresAt = $this->request->getPost('expires_at');

        if ($this->courseEnrollments->approveEnrollment($id, $adminId, $expiresAt)) {
            return redirect()->back()->with('success', 'تم الموافقة على الطلب وتفعيل الدورة بنجاح');
        } else {
            return redirect()->back()->with('error', 'فشل في الموافقة على الطلب');
        }
    }

    /**
     * Reject course enrollment request
     */
    public function rejectCourseEnrollment($id)
    {
        $reason = $this->request->getPost('rejection_reason');

        if ($this->courseEnrollments->rejectEnrollment($id, $reason)) {
            return redirect()->back()->with('success', 'تم رفض الطلب');
        } else {
            return redirect()->back()->with('error', 'فشل في رفض الطلب');
        }
    }

    /**
     * Get course enrollment statistics
     */
    public function getCourseEnrollmentStats()
    {
        $stats = $this->courseEnrollments->getEnrollmentStats();
        return $this->response->setJSON($stats);
    }

    /**
     * Get pending course enrollments count
     */
    public function getPendingCourseEnrollmentsCount()
    {
        $count = $this->courseEnrollments->where('status', 'pending')->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }
}
