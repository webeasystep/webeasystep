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

            // Example: Join with `users`, `tb_courses`, and also `tb_payments`
            //          so we can display payment info (method, status, amount)
            $builder = $this->enrollments
                ->select("
                tb_enrollments.id,
                users.username AS user_name,
                tb_courses.course_name,
                tb_enrollments.status AS enrollment_status,
                tb_enrollments.enrolled_at,
                tb_payments.payment_method,
                tb_payments.payment_status,
                tb_payments.amount
            ")
                ->join('users', 'users.id = tb_enrollments.user_id', 'left')
                ->join('tb_courses', 'tb_courses.id = tb_enrollments.course_id', 'left')
                // Join payments by matching user_id & course_id
                ->join('tb_payments', 'tb_payments.user_id = tb_enrollments.user_id AND tb_payments.course_id = tb_enrollments.course_id', 'left')
                ->orderBy('tb_enrollments.id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['user_name', 'course_name', 'enrollment_status', 'payment_method', 'payment_status']);
            DtTable::orderableColumns(['user_name', 'course_name', 'enrollment_status', 'payment_method', 'payment_status', 'amount', 'enrolled_at']);
            $output = DtTable::tableRender($builder, false);
            DtTable::setShowColumns("user_name,course_name,enrollment_status,payment_method,payment_status,amount,enrolled_at");

            return $this->response->setJSON($output);
        }

        // Otherwise, show a normal admin listing page
        return view('index', $data);
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

        return view('form', $data);
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

        return view('form', $data);
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
