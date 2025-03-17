<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use App\Libraries\FireUploader;
use Modules\Enrollments\Models\EnrollmentsModel;

class AdminEnrollments extends BaseController
{
    protected EnrollmentsModel $enrollments;
    protected array $rules;
    protected FireUploader $fireUploader;

    public function __construct()
    {
        // Initialize the FireUploader for file uploads
        $this->fireUploader = new FireUploader();

        // Model instance
        $this->enrollments = new EnrollmentsModel();

        // Validation rules (adjust as needed for your fields)
        $this->rules = [
            'user_id' => [
                'label' => 'المستخدم',
                'rules' => 'required|integer',
            ],
            'course_id' => [
                'label' => 'الدورة',
                'rules' => 'required|integer',
            ],
            'status' => [
                'label' => 'الحالة',
                // If your table uses these enumerations: active, completed, cancelled
                'rules' => 'required|in_list[active,completed,cancelled]',
            ],
            // Example if you want to require a file for proof:
            // 'proof' => [
            //     'label' => 'إثبات الدفع',
            //     'rules' => 'permit_empty|uploaded[proof]|ext_in[proof,jpg,png,pdf]',
            // ],
        ];
    }

    /**
     * Show a listing of all enrollments, possibly with DataTables (DtTable).
     */
    public function index()
    {
        $data['title'] = 'قائمة الاشتراكات'; // Adjust as you like

        // If it's an AJAX request from DataTables
        if ($this->request->isAJAX()) {
            // Build your query. For example, select needed columns:
            $builder = $this->enrollments
                ->select('id, user_id, course_id, status, enrolled_at')
                ->orderBy('id', 'desc')
                ->builder();
            // dtTable usage (similar to AdminCourses)
            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['user_id', 'course_id', 'status']);
            DtTable::orderableColumns(['user_id', 'course_id', 'status', 'enrolled_at']);
            // DtTable::setColumnSwitch('status');
            // Render the output
            $output = DtTable::tableRender($builder, false);
            // Which columns to show in the final table
            DtTable::setShowColumns("user_id,course_id,status,enrolled_at");

            return $this->response->setJSON($output);
        }

        // Otherwise, show the admin listing view
        return view('admin/index', $data);
    }

    /**
     * Add a new enrollment
     */
    public function add()
    {
        $data['title'] = 'إضافة اشتراك جديد';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate($this->rules)) {
                // Insert the data
                $id = $this->data_arr(); // Creates a new row in tb_enrollments

                // If you want to upload a file for proof or something:
                // $this->fireUploader->upload_photos($this->enrollments, 'proof', $id);

                $this->show_msg('success', 'تمت الإضافة بنجاح', 'تم إضافة الاشتراك بنجاح');
                return redirect()->to(ADMIN_URL . 'enrollments');
            } else {
                $this->show_msg('danger', 'أخطاء في الإدخال', validation_errors());
            }
        }

        // For a dropdown of courses or users, you can do:
        $data['courses_list'] = $this->enrollments->get_courses_list();
        $data['users_list']   = $this->enrollments->get_users_list();

        return view('admin/form', $data);
    }

    /**
     * Edit an existing enrollment
     */
    public function edit($id)
    {
        $data['title'] = 'تعديل الاشتراك';

        if ($this->request->getMethod() === 'post') {
            if ($this->validate($this->rules)) {
                // Update the data
                $this->data_arr($id);

                // If you have a file to update:
                // $this->fireUploader->upload_photos($this->enrollments, 'proof', $id);

                $this->show_msg('success', 'تم التعديل', 'تم تعديل بيانات الاشتراك بنجاح');
                return redirect()->to(ADMIN_URL . 'enrollments');
            } else {
                $this->show_msg('danger', 'أخطاء في الإدخال', validation_errors());
            }
        }

        // Fetch existing enrollment
        $enrollment = $this->enrollments->find($id);
        if (!$enrollment) {
            $this->show_msg('danger', 'خطأ', 'لم يتم العثور على الاشتراك المطلوب');
            return redirect()->to(ADMIN_URL . 'enrollments');
        }

        $data['enrollment'] = $enrollment;

        // If you had stored any JSON or attachments, fetch them here
        // $data['files'] = json_decode($enrollment->proof ?? '[]', true);

        // For dropdowns
        $data['courses_list'] = $this->enrollments->get_courses_list();
        $data['users_list']   = $this->enrollments->get_users_list();

        return view('admin/form', $data);
    }

    /**
     * Insert or Update data in tb_enrollments
     */
    private function data_arr($id = null)
    {
        $builder = $this->db->table('tb_enrollments');

        $data = [
            'user_id' => $this->request->getPost('user_id', FILTER_SANITIZE_NUMBER_INT),
            'course_id' => $this->request->getPost('course_id', FILTER_SANITIZE_NUMBER_INT),
            // We rely on DB default for enrolled_at if needed
            'status' => $this->request->getPost('status', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];

        // If you had extra fields like amount or enrollment_method, add them:
        // 'amount' => $this->request->getPost('amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        // 'enrollment_method' => $this->request->getPost('enrollment_method'),

        if ($id) {
            // Update existing record
            $builder->where('id', $id)->update($data);
        } else {
            // Insert new record
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        return $id;
    }

}
