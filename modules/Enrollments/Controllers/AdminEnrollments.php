<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use App\Libraries\FireUploader;
use Modules\Enrollments\Models\EnrollmentsModel;
use Modules\Enrollments\Models\UnitEnrollmentsModel;
use Modules\Units\Models\UnitsModel;
use Modules\Users\Models\UsersModel;

class AdminEnrollments extends BaseController
{
    protected EnrollmentsModel $enrollments;
    protected UnitEnrollmentsModel $unitEnrollments;
    protected UnitsModel $unitsModel;
    protected UsersModel $usersModel;
    protected array $rules;
    protected FireUploader $fireUploader;
    protected string $table = 'tb_unit_enrollments';

    public function __construct()
    {
        // Initialize the FireUploader for file uploads
        $this->fireUploader = new FireUploader();

        // Model instances
        $this->enrollments = new EnrollmentsModel();
        $this->unitEnrollments = new UnitEnrollmentsModel();
        $this->unitsModel = new UnitsModel();
        $this->usersModel = new UsersModel();

        // Validation rules for tb_unit_enrollments
        $this->rules = [
            'user_id' => [
                'label' => 'المستخدم',
                'rules' => 'required|integer',
            ],
            'unit_ids' => [
                'label' => 'الوحدات',
                'rules' => 'required',
            ],
            'total_amount' => [
                'label' => 'المبلغ الإجمالي',
                'rules' => 'required|decimal',
            ],
            'payment_method' => [
                'label' => 'طريقة الدفع',
                'rules' => 'required|in_list[bank_transfer,credit_card,paypal,cash]',
            ],
            'status' => [
                'label' => 'الحالة',
                'rules' => 'required|in_list[pending,approved,rejected]',
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

            // Join with `users` for unit enrollment display
            $builder = $this->unitEnrollments
                ->select("
                tb_unit_enrollments.id,
                users.username AS user_name,
                tb_unit_enrollments.unit_ids,
                tb_unit_enrollments.total_amount,
                tb_unit_enrollments.payment_method,
                tb_unit_enrollments.status AS enrollment_status,
                tb_unit_enrollments.created_at,
                tb_unit_enrollments.processed_at,
                tb_unit_enrollments.admin_notes
            ")
                ->join('users', 'users.id = tb_unit_enrollments.user_id', 'left')
                ->orderBy('tb_unit_enrollments.id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['user_name', 'email', 'enrollment_status', 'payment_method']);
            DtTable::orderableColumns(['user_name', 'enrollment_status', 'created_at', 'total_amount']);
            DtTable::setShowColumns("user_name,email,unit_ids,total_amount,enrollment_status,created_at,processed_at");
            $output = DtTable::tableRender($builder, false);

            return $this->response->setJSON($output);
        }

        // Otherwise, show a normal admin listing page
        return view('index', $data);
    }



    /**
     * Add a new unit enrollment
     */
    public function add()
    {
        $data['title'] = 'إضافة طلب شراء وحدات جديد';

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                // Insert the data
                $id = $this->data_arr(); // Creates a new row in tb_unit_enrollments

                // Upload payment proof if provided
                $this->fireUploader->upload_photos($this->unitEnrollments, 'payment_proof', $id);

                $this->show_msg('success', 'تمت الإضافة بنجاح', 'تم إضافة طلب شراء الوحدات بنجاح');
                return redirect()->to(ADMIN_URL . 'enrollments');
            } else {
                $this->show_msg('danger', 'أخطاء في الإدخال', validation_errors());
            }
        }

        // For dropdowns
        $data['units'] = $this->unitEnrollments->get_units_list();
        $data['users'] = $this->unitEnrollments->get_users_list();

        return view('form', $data);
    }

    /**
     * Edit an existing unit enrollment
     */
    public function edit($id)
    {
        $data['title'] = 'تعديل طلب شراء الوحدات';

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                // Update the data
                $this->data_arr($id);

                // Update payment proof if provided
                $this->fireUploader->upload_photos($this->unitEnrollments, 'payment_proof', $id);

                $this->show_msg('success', 'تم التعديل', 'تم تعديل بيانات طلب الشراء بنجاح');
                return redirect()->to(ADMIN_URL . 'enrollments');
            } else {
                $this->show_msg('danger', 'أخطاء في الإدخال', validation_errors());
            }
        }

        // Fetch existing unit enrollment
        $enrollment = $this->unitEnrollments->find($id);
        if (!$enrollment) {
            $this->show_msg('danger', 'خطأ', 'لم يتم العثور على طلب الشراء المطلوب');
            return redirect()->to(ADMIN_URL . 'enrollments');
        }

        $data['enrollment'] = $enrollment;

        // Decode unit_ids JSON for form display
        $data['selected_units'] = json_decode($enrollment->unit_ids ?? '[]', true);

        // For dropdowns
        $data['units'] = $this->unitEnrollments->get_units_list();
        $data['users'] = $this->unitEnrollments->get_users_list();

        return view('form', $data);
    }

    /**
     * Insert or Update data in tb_unit_enrollments
     */
    private function data_arr($id = null)
    {
        $builder = $this->db->table('tb_unit_enrollments');

        $unitIds = $this->request->getPost('unit_ids');
        if (is_array($unitIds)) {
            $unitIds = json_encode($unitIds);
        }

        $data = [
            'user_id' => $this->request->getPost('user_id', FILTER_SANITIZE_NUMBER_INT),
            'unit_ids' => $unitIds,
            'total_amount' => $this->request->getPost('total_amount', FILTER_SANITIZE_NUMBER_FLOAT) ?: 0,
            'payment_method' => $this->request->getPost('payment_method', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'bank_transfer',
            'status' => $this->request->getPost('status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'pending',
        ];

        // Add payment proof if provided
        if ($this->request->getPost('payment_proof')) {
            $data['payment_proof'] = $this->request->getPost('payment_proof', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

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

    /**
     * Display unit enrollment requests
     */
    public function unitEnrollments()
    {
        $data['title'] = 'طلبات شراء الوحدات';

        if ($this->request->isAJAX()) {
            $enrollmentsModel = $this->unitEnrollments
                ->select('tb_unit_enrollments.*, users.username, users.email')
                ->join('users', 'users.id = tb_unit_enrollments.user_id')
                ->orderBy('tb_unit_enrollments.created_at', 'DESC')
                ->builder();

            DtTable::hideColumns(['id', 'user_id', 'unit_ids']);
            DtTable::searchableColumns(['username', 'email', 'status']);
            DtTable::orderableColumns(['username', 'total_amount', 'status', 'created_at']);
            DtTable::setShowColumns('username,email,total_amount,payment_method,status,created_at');

            $output = DtTable::tableRender($enrollmentsModel, false);
            return $this->response->setJSON($output);
        } else {
            return view('Admin/unit_enrollments', $data);
        }
    }

    /**
     * View unit enrollment details
     */
    public function showUnitEnrollment($id)
    {
        $enrollment = $this->unitEnrollments->getEnrollmentWithUnits($id);
        if (!$enrollment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('طلب الشراء غير موجود');
        }

        $data = [
            'title' => 'تفاصيل طلب شراء الوحدات',
            'enrollment' => $enrollment
        ];

        return view('Admin/unit_enrollment_details', $data);
    }

    /**
     * Approve unit enrollment request
     */
    public function approveUnitEnrollment($id)
    {
        $adminId = auth()->user()->id;
        $notes = $this->request->getPost('admin_notes');

        if ($this->unitEnrollments->approveEnrollment($id, $adminId, $notes)) {
            return redirect()->back()->with('success', 'تم الموافقة على الطلب وتفعيل الوحدات بنجاح');
        } else {
            return redirect()->back()->with('error', 'فشل في الموافقة على الطلب');
        }
    }

    /**
     * Reject unit enrollment request
     */
    public function rejectUnitEnrollment($id)
    {
        $adminId = auth()->user()->id;
        $notes = $this->request->getPost('admin_notes');

        if ($this->unitEnrollments->rejectEnrollment($id, $adminId, $notes)) {
            return redirect()->back()->with('success', 'تم رفض الطلب');
        } else {
            return redirect()->back()->with('error', 'فشل في رفض الطلب');
        }
    }

    /**
     * Get unit enrollment statistics
     */
    public function unitEnrollmentStats()
    {
        $stats = $this->unitEnrollments->getEnrollmentStats();
        return $this->response->setJSON($stats);
    }

    /**
     * Get pending unit enrollments count
     */
    public function getPendingUnitEnrollmentsCount()
    {
        $count = $this->unitEnrollments->where('status', 'pending')->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }
}
