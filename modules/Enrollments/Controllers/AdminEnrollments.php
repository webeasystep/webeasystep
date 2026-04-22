<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Enrollments\Models\CourseEnrollmentsModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Users\Models\UsersModel;

class AdminEnrollments extends BaseController
{
    protected CourseEnrollmentsModel $courseEnrollments;
    protected CoursesModel $coursesModel;
    protected UsersModel $usersModel;
    protected string $table = 'tb_course_enrollments';

    public function __construct()
    {
        $this->courseEnrollments = new CourseEnrollmentsModel();
        $this->coursesModel = new CoursesModel();
        $this->usersModel = new UsersModel();
    }

    /**
     * Show a listing of all course enrollments with DataTables
     */
    public function index()
    {

        $data['title'] = 'طلبات شراء الدورات';

        if ($this->request->isAJAX()) {
            $builder = $this->courseEnrollments->getDataTable()->builder();

            DtTable::hideColumns(['id', 'user_id', 'course_id', 'approved_at', 'approved_by', 'expires_at', 'notes', 'updated_at']);
            DtTable::searchableColumns(['full_name', 'mobile', 'course_title', 'payment_proof', 'status']);
            DtTable::orderableColumns(['full_name', 'course_title', 'paid_amount', 'status', 'created_at']);
            DtTable::setShowColumns('full_name,mobile,course_title,paid_amount,payment_method,payment_proof,status,created_at');
            // Add formatter for payment_proof column to make it clickable
            DtTable::changeColumn('payment_proof', function ($value) {
                if ($value) {
                    // $value is already saved as 'uploads/enrollments/filename.ext'
                    $url = base_url($value);
                    return '<a href="' . esc($url) . '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-image"></i> عرض الإثبات</a>';
                }
                return '<span class="badge badge-secondary">لا يوجد إثبات</span>';
            });

            $output = DtTable::tableRender($builder, false);
            return $this->response->setJSON($output);
        }

        return view('index', $data);
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");
        $data['users'] = $this->usersModel->select('id, full_name')->findAll();
        $data['users'] = array_column($data['users'], 'full_name', 'id');
        $data['courses'] = $this->coursesModel->select('id, course_title')->findAll();
        $data['courses'] = array_column($data['courses'], 'course_title', 'id');

        if ($this->request->is('post')) {
            $rules = [
                'user_id' => 'required',
                'course_id' => 'required',
                'status' => 'required',
            ];
            if ($this->validate($rules)) {
                $this->data_arr();
                return redirect()->to(ADMIN_URL . "enrollments")->with('success', lang("Admin.add_success"));
            } else {
                return redirect()->back()->with('error', validation_errors());
            }
        }
        return view("form", $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");
        $data['users'] = $this->usersModel->select('id, full_name')->findAll();
        $data['users'] = array_column($data['users'], 'full_name', 'id');
        $data['courses'] = $this->coursesModel->select('id, course_title')->findAll();
        $data['courses'] = array_column($data['courses'], 'course_title', 'id');

        if ($this->request->is('post')) {
            $rules = [
                'user_id' => 'required',
                'course_id' => 'required',
                'status' => 'required',
            ];
            if ($this->validate($rules)) {
                $this->data_arr($id);
                return redirect()->to(ADMIN_URL . "enrollments")->with('success', lang("Admin.edit_success"));
            } else {
                return redirect()->back()->with('error', validation_errors());
            }
        }

        $data['enrollment'] = $this->courseEnrollments->find($id);
        if (!$data['enrollment']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Setup files for fireuploader if payment proof exists
        $files = [];
        if (!empty($data['enrollment']->payment_proof)) {
            $files[] = [
                'full_path' => $data['enrollment']->payment_proof,
                'name' => basename($data['enrollment']->payment_proof)
            ];
        }
        $data['files'] = $files;

        return view('form', $data);
    }

    private function data_arr($id = null)
    {
        $builder = $this->db->table('tb_course_enrollments');

        $data = [
            'user_id'        => $this->request->getPost('user_id'),
            'course_id'      => $this->request->getPost('course_id'),
            'paid_amount'    => $this->request->getPost('paid_amount') ?? 0,
            'payment_method' => $this->request->getPost('payment_method'),
            'status'         => $this->request->getPost('status'),
            'notes'          => $this->request->getPost('notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $isNewlyApproved = false;
        if ($data['status'] === 'approved' && (!$id || $this->courseEnrollments->find($id)->status !== 'approved')) {
            $data['approved_at'] = date('Y-m-d H:i:s');
            $data['approved_by'] = auth()->user()->id;
            $isNewlyApproved = true;
        }

        // Handle file upload
        // Note: For a complete implementation, fireuploader needs a proper model method to handle files,
        // but here we can just accept it or let existing logic be.
        // The fireuploader uploads images directly and we should extract it from post data if necessary.

        if ($id) {
            $builder->where('id', $id)->update($data);
            if ($isNewlyApproved) {
                $this->sendApprovalEmail($id);
            }
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $builder->insert($data);
            $id = $this->db->insertID();
            if ($isNewlyApproved) {
                $this->sendApprovalEmail($id);
            }
        }

        return $id;
    }


    /**
     * View course enrollment details
     */
    public function showCourseEnrollment($id)
    {
        $enrollment = $this->courseEnrollments
            ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.course_price, users.full_name, users.email, auth_identities.secret as mobile')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "mobile_password"', 'left')
            ->find($id);

        if (!$enrollment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('طلب الشراء غير موجود');
        }

        $data = [
            'title' => 'تفاصيل طلب شراء الدورة',
            'enrollment' => $enrollment
        ];

        return view('Admin\course_enrollment_details', $data);
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
            $this->sendApprovalEmail($id);
            return redirect()->back()->with('success', 'تم الموافقة على الطلب وتفعيل الدورة بنجاح');
        } else {
            return redirect()->back()->with('error', 'فشل في الموافقة على الطلب');
        }
    }

    /**
     * Send course approval email
     */
    private function sendApprovalEmail($enrollmentId)
    {
        $enrollment = $this->courseEnrollments
            ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.slug, users.full_name, auth_identities.secret as email')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->find($enrollmentId);

        if (!$enrollment || empty($enrollment->email)) {
            log_message('error', 'Could not send approval email for enrollment ID: ' . $enrollmentId . ' - User or email not found.');
            return false;
        }

        $email = \Config\Services::email();
        $email->setTo($enrollment->email);
        $email->setSubject('تم تفعيل اشتراكك في دورة: ' . $enrollment->course_title);

        $courseUrl = base_url('courses/course_view/' . $enrollment->slug);

        $message = MainView('Modules\Enrollments\Views\Site\emails\course_approved', [
            'full_name' => $enrollment->full_name,
            'course_title' => $enrollment->course_title,
            'course_url' => $courseUrl
        ]);

        $email->setMessage($message);
        $email->setMailType('html');

        $success = $email->send();
        if (!$success) {
            log_message('error', 'Failed to send approval email: ' . $email->printDebugger(['headers']));
        }

        return $success;
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
