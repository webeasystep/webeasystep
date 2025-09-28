<?php

namespace Modules\Units\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use App\Libraries\FireUploader;
use Modules\Courses\Models\CoursesModel;
// use Modules\Courses\Models\SectionsModel; // Removed - sections table will be dropped
use Modules\Units\Models\UnitsModel;
use Modules\Units\Models\UnitItemsModel;
use Modules\Units\Models\PaymentAttachmentsModel;
use Modules\Units\Models\UnitPurchasesModel;
use Modules\Quizzes\Models\QuizzesModel;
use Modules\Pages\Models\PagesModel;
use Modules\Units\Services\BunnyNetService;
use CodeIgniter\HTTP\RedirectResponse;

class AdminUnits extends BaseController
{
    protected UnitsModel $units;
    protected UnitItemsModel $unitItems;
    protected PaymentAttachmentsModel $payments;
    protected UnitPurchasesModel $purchases;
    protected CoursesModel $coursesModel;
    // protected SectionsModel $sectionsModel; // Removed - sections table will be dropped
    protected QuizzesModel $quizzesModel;
    protected PagesModel $pagesModel;
    protected array $rules;
    protected FireUploader $fireUploader;
    protected BunnyNetService $bunnyNetService;

    public function __construct()
    {
        $this->fireUploader = new FireUploader();
        $this->units = new UnitsModel();
        $this->unitItems = new UnitItemsModel();
        $this->payments = new PaymentAttachmentsModel();
        $this->purchases = new UnitPurchasesModel();
        $this->coursesModel = new CoursesModel();
        // $this->sectionsModel = new SectionsModel(); // Removed - sections table will be dropped
        $this->quizzesModel = new QuizzesModel();
        $this->pagesModel = new PagesModel();
        $this->bunnyNetService = new BunnyNetService();

        $this->rules = [
            'unit_name' => ['label' => lang('Units.unit_name'), 'rules' => 'required|min_length[3]'],
            'unit_desc' => ['label' => lang('Units.unit_desc'), 'rules' => 'required'],
            'course_id' => ['label' => lang('Units.course_title'), 'rules' => 'required|numeric'],
            'sort_order' => ['label' => lang('Units.sort_order'), 'rules' => 'required|numeric'],
            'price' => ['label' => lang('Units.unit_price'), 'rules' => 'permit_empty|decimal'],
        ];
    }

    /**
     * Display units management interface (Articles style)
     */
    public function index()
    {
        $data['title'] = lang('Units.units_management');
        $data['courses'] = $this->coursesModel->where('active', 1)->findAll();
        // $data['sections'] = $this->sectionsModel->where('active', 1)->findAll(); // Removed - sections table will be dropped
        if ($this->request->isAJAX()) {
            $unitsModel = $this->units
                ->select('tb_units.id,tb_units.unit_name,tb_units.sort_order,tb_units.active,tb_units.created_at,tb_courses.course_title')
                ->join('tb_courses', 'tb_courses.id = tb_units.course_id')
                ->orderBy('tb_courses.course_title', 'ASC')
                ->orderBy('tb_units.sort_order', 'ASC')
                ->builder();


            // Set up custom filters
            DtTable::setCustomFilter('course_id', function($query, $value) {
                if ($value && $value !== '') {
                    $query->where('tb_units.course_id', $value);
                }
            });

            DtTable::setCustomFilter('active', function($query, $value) {
                if ($value !== '' && $value !== null) {
                    $query->where('tb_units.active', $value);
                }
            });

            DtTable::hideColumns(['id']);
            DtTable::setColumnSwitch('active');
            DtTable::searchableColumns(['unit_name', 'course_title', 'section_name']);
            DtTable::orderableColumns(['unit_name', 'course_title', 'section_name', 'sort_order', 'price']);
            DtTable::setShowColumns('unit_name,course_title,section_name,price,active,sort_order');

            $output = DtTable::tableRender($unitsModel, false);

            return $this->response->setJSON($output);
        } else {

            return view('index', $data);
        }
    }


    /**
     * Data array processing (Articles style)
     */
    public function data_arr($id = null)
    {
        $builder = $this->db->table('tb_units');

        // Sanitize and prepare data
        $data = [
            'course_id' => $this->request->getPost('course_id', FILTER_SANITIZE_NUMBER_INT),
            'unit_name' => $this->request->getPost('unit_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'unit_desc' => $this->request->getPost('unit_desc'),
            'sort_order' => $this->request->getPost('sort_order', FILTER_SANITIZE_NUMBER_INT),
            'price' => $this->request->getPost('unit_price') ? floatval($this->request->getPost('unit_price')) : 0.00,
            'is_free' => $this->request->getPost('is_free') ? '1' : '0',
            // 'is_purchasable' => $this->request->getPost('is_purchasable') ? '1' : '0', // Column doesn't exist
            'active' => $this->request->getPost('active') ? '1' : '0',
            // 'unit_type' => 'video', // Column doesn't exist
            // 'content_data' => json_encode([]) // Column doesn't exist
        ];

        if ($id) {
            // Update existing record
            $builder->where('id', $id);
            $builder->update($data);
        } else {
            // Insert new record
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        // Handle unit items if provided (for both add and edit)
        $unitItemsJson = $this->request->getPost('unit_items');

        if (!empty($unitItemsJson)) {
            $unitItems = json_decode($unitItemsJson, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($unitItems) && !empty($unitItems)) {
                // If editing (id exists), clear existing unit items first to prevent duplication
                if ($id) {
                    $this->db->table('tb_unit_items')->where('unit_id', $id)->delete();
                }
                $this->saveUnitItems($id, $unitItems);
            }
        }

        // Handle quiz assignment if provided (for both add and edit)
        $quizIds = $this->request->getPost('quiz_ids');
        if (!empty($quizIds)) {
            $this->assignQuizzesToUnit($id, $quizIds);
        } elseif ($id) {
            // If editing and no quizzes selected, clear existing assignments
            $this->assignQuizzesToUnit($id, []);
        }

        return $id;
    }

    /**
     * Payment attachments management
     */
    public function payments()
    {
        $data['title'] = lang('Units.payment_attachments');

        if ($this->request->isAJAX()) {
            $paymentsModel = $this->payments
                ->select('tb_payment_attachments.*, users.username, users.email')
                ->join('users', 'users.id = tb_payment_attachments.user_id', 'left')
                ->orderBy('tb_payment_attachments.created_at', 'DESC')
                ->builder();

            DtTable::hideColumns(['id', 'user_id']);
            DtTable::searchableColumns(['username', 'email', 'status']);
            DtTable::orderableColumns(['username', 'total_price', 'status', 'created_at']);
            DtTable::setShowColumns('username,email,total_price,payment_method,status,created_at');

            $output = DtTable::tableRender($paymentsModel, false);
            return $this->response->setJSON($output);
        } else {
            return view('payments_index', $data);
        }
    }

    /**
     * View payment details
     */
    public function viewPayment($id)
    {
        $payment = $this->payments->getPaymentById($id);
        if (!$payment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(lang('Units.payment_not_found'));
        }

        $data['title'] = lang('Units.payment_details');
        $data['payment'] = $payment;

        // Get unit details for this payment
        $unitIds = json_decode($payment->unit_ids, true);
        $data['units'] = [];
        if ($unitIds) {
            $data['units'] = $this->units->whereIn('id', $unitIds)->findAll();
        }

        return view('payment_details', $data);
    }

    /**
     * Approve payment
     */
    public function approvePayment($id)
    {
        $adminId = auth()->user()->id;
        $notes = $this->request->getPost('admin_notes');

        if ($this->payments->approvePayment($id, $adminId, $notes)) {
            $this->show_msg('success', lang('Units.payment_approved'), lang('Units.payment_approved_success'));
        } else {
            $this->show_msg('danger', lang('Units.error'), lang('Units.payment_approval_failed'));
        }

        return redirect()->to(ADMIN_URL . 'units/payments');
    }

    /**
     * Reject payment
     */
    public function rejectPayment($id)
    {
        $adminId = auth()->user()->id;
        $notes = $this->request->getPost('admin_notes');

        if ($this->payments->rejectPayment($id, $adminId, $notes)) {
            $this->show_msg('success', lang('Units.payment_rejected'), lang('Units.payment_rejected_success'));
        } else {
            $this->show_msg('danger', lang('Units.error'), lang('Units.payment_rejection_failed'));
        }

        return redirect()->to(ADMIN_URL . 'units/payments');
    }

    /**
     * Unit purchases management
     */
    public function purchases()
    {
        $data['title'] = lang('Units.unit_purchases');

        if ($this->request->isAJAX()) {
            $purchasesModel = $this->purchases
                ->select('tb_unit_purchases.*, users.username, tb_units.unit_name')
                ->join('users', 'users.id = tb_unit_purchases.user_id', 'left')
                ->join('tb_units', 'tb_units.id = tb_unit_purchases.unit_id', 'left')
                ->orderBy('tb_unit_purchases.created_at', 'DESC')
                ->builder();

            DtTable::hideColumns(['id', 'user_id', 'unit_id']);
            DtTable::searchableColumns(['username', 'unit_name']);
            DtTable::orderableColumns(['username', 'unit_name', 'price_paid', 'access_granted', 'created_at']);
            DtTable::setColumnSwitch('access_granted');
            DtTable::setShowColumns('username,unit_name,price_paid,access_granted,access_expires_at,created_at');

            $output = DtTable::tableRender($purchasesModel, false);
            return $this->response->setJSON($output);
        } else {
            return view('purchases_index', $data);
        }
    }

    /**
     * Add new unit (Articles style)
     */
    public function add()
    {
        $data['title'] = lang('Admin.add_data');

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr();
                $this->show_msg('success', lang('Admin.add_operation'), lang('Admin.add_success'));
                return redirect()->to(ADMIN_URL . 'units');
            } else {
                $this->show_msg('danger', lang('Admin.validation_errors'), validation_errors());
            }
        }

        $data['courses'] = $this->coursesModel->where('active', 1)->findAll();
        $data['sections'] = [];
        $data['quizzes'] = [];

        return view('form', $data);
    }

    /**
     * Edit unit (Articles style)
     */
    public function edit($id)
    {
        $data['title'] = lang('Admin.edit_data');

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr($id);
                $this->show_msg('success', lang('Admin.edit'), lang('Admin.edit_success'));
                return redirect()->to(ADMIN_URL . 'units');
            } else {
                $this->show_msg('danger', lang('Admin.validation_errors'), validation_errors());
            }
        }

        // Fetch the unit
        $data['unit'] = $this->units->getUnitById($id);
        if (!$data['unit']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(lang('Units.unit_not_found'));
        }

        $course = $this->coursesModel->find($data['unit']->course_id);

        $data['courses'] = $this->coursesModel->where('active', 1)->findAll();
        // Sections are no longer used - units are directly associated with courses
        $data['selected_course_id'] = $course->id;
        $data['sections'] = []; // Empty array for backward compatibility
        $data['quizzes'] = $this->quizzesModel->where('course_id', $data['unit']->course_id)->findAll();
        $data['assigned_quizzes'] = $this->getUnitQuizzes($id);

        // Load existing unit items for editing
        $unitItems = $this->db->table('tb_unit_items')
                             ->where('unit_id', $id)
                             ->orderBy('sort_order', 'ASC')
                             ->get()
                             ->getResultArray();
        $data['existing_unit_items'] = json_encode($unitItems);

        return view('form', $data);
    }

    /**
     * Delete unit
     */
    public function deleteUnit($id): \CodeIgniter\HTTP\RedirectResponse
    {
        $unit = $this->units->find($id);
        if (!$unit) {
            return redirect()->back()->with('error', 'الوحدة غير موجودة');
        }

        if ($this->units->delete($id)) {
            return redirect()->to(ADMIN_URL . 'units')->with('success', 'تم حذف الوحدة بنجاح');
        }

        return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الوحدة');
    }



    /**
     * Get quizzes by course ID (AJAX)
     */
    public function getQuizzesByCourse($courseId)
    {
        $quizzes = $this->quizzesModel->where('course_id', $courseId)
                                     ->where('active', 1)
                                     ->findAll();

        return $this->response->setJSON($quizzes);
    }

    /**
     * Show unit details
     */
    public function show($id): \CodeIgniter\HTTP\ResponseInterface
    {
        $unit = $this->units->find($id);
        if (!$unit) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('الوحدة غير موجودة');
        }

        $course = $this->coursesModel->find($unit->course_id);
        $assignedQuizzes = $this->getUnitQuizzes($id);

        $quizzes = [];
        if (!empty($assignedQuizzes)) {
            $quizIds = array_column($assignedQuizzes, 'quiz_id');
            $quizzes = $this->quizzesModel->whereIn('id', $quizIds)->findAll();
        }

        $data = [
            'title' => 'تفاصيل الوحدة: ' . $unit->unit_name,
            'unit' => $unit,
            'course' => $course,
            'quizzes' => $quizzes
        ];

        return $this->response->setBody(view('show', $data));
    }

    /**
     * Get units statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => $this->units->countAll(),
            'active' => $this->units->where('active', 1)->countAllResults(false),
            'preview' => $this->units->where('is_free', 1)->countAllResults(false),
            'with_quizzes' => $this->db->table('tb_quizzes')
                                      ->select('DISTINCT unit_id')
                                      ->where('active', 1)
                                      ->countAllResults()
        ];

        return $this->response->setJSON($stats);
    }

    /**
     * Get unit specific statistics
     */
    public function unitStatistics($unitId)
    {
        // This would require integration with Progress module
        $stats = [
            'views' => 0,
            'completions' => 0,
            'completion_rate' => 0
        ];

        // If Progress module is available, get real statistics
        if (class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            $stats['views'] = $progressModel->where('unit_id', $unitId)->countAllResults(false);
            $stats['completions'] = $progressModel->where('unit_id', $unitId)
                                                  ->where('is_completed', 1)
                                                  ->countAllResults();

            if ($stats['views'] > 0) {
                $stats['completion_rate'] = round(($stats['completions'] / $stats['views']) * 100, 1);
            }
        }

        return $this->response->setJSON($stats);
    }

    /**
     * Duplicate unit
     */
    public function duplicate($id)
    {
        $unit = $this->units->find($id);
        if (!$unit) {
            return $this->response->setJSON(['success' => false, 'message' => 'الوحدة غير موجودة']);
        }

        // Create new unit data
        $newUnitData = [
            'course_id' => $unit->course_id,
            'unit_name' => $unit->unit_name . ' (نسخة)',
            'unit_desc' => $unit->unit_desc,
            'sort_order' => $this->getNextSortOrder($unit->course_id),
            'is_free' => $unit->is_free,
            'active' => 0, // New units start as inactive
            // 'unit_type' => $unit->unit_type, // Column doesn't exist
            'unit_type' => 'video',
            // 'content_data' => $unit->content_data // Column doesn't exist
        ];

        if ($newUnitId = $this->units->insert($newUnitData)) {
            // Note: Quizzes are associated with courses, not individual units
            // No need to copy quiz assignments as they are course-level

            return $this->response->setJSON([
                'success' => true,
                'message' => 'تم نسخ الوحدة بنجاح',
                'new_unit_id' => $newUnitId
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'حدث خطأ أثناء نسخ الوحدة']);
    }

    /**
     * Remove quiz from unit
     * Note: Since quizzes are course-level, this function is no longer applicable
     */
    public function removeQuiz()
    {
        // Quizzes are associated with courses, not individual units
        // This operation is not supported in the current architecture
        return $this->response->setJSON([
            'success' => false,
            'message' => 'الاختبارات مرتبطة بالكورس وليس بالوحدة الفردية'
        ]);
    }

    /**
     * Toggle unit status (AJAX)
     */
    public function toggleStatus($id)
    {
        $unit = $this->units->find($id);
        if (!$unit) {
            return $this->response->setJSON(['success' => false, 'message' => 'الوحدة غير موجودة']);
        }

        $newStatus = $unit->active ? 0 : 1;

        if ($this->units->update($id, ['active' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $newStatus ? 'تم تفعيل الوحدة' : 'تم إلغاء تفعيل الوحدة',
                'new_status' => $newStatus
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'حدث خطأ أثناء تغيير الحالة']);
    }

    /**
     * Assign quizzes to unit
     * Note: Since quizzes are course-level, this function is no longer applicable
     */
    private function assignQuizzesToUnit($unitId, $quizIds)
    {
        // Quizzes are associated with courses, not individual units
        // No assignment operation needed as quizzes are course-level
        // This function is kept for backward compatibility but does nothing
        return true;
    }

    /**
     * Get quizzes assigned to unit
     */
    private function getUnitQuizzes($unitId)
    {
        // Get the unit's course_id first
        $unit = $this->units->find($unitId);
        if (!$unit) {
            return [];
        }

        // Get quizzes by course_id instead of unit_id
        return $this->db->table('tb_quizzes')
                       ->select('id as quiz_id')
                       ->where('course_id', $unit->course_id)
                       ->where('active', 1)
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get units by course ID (AJAX)
     */
    public function getUnitsByCourse($courseId)
    {
        $units = $this->units->select('tb_units.id, tb_units.unit_name')
                            ->where('tb_units.course_id', $courseId)
                            ->where('tb_units.active', 1)
                            ->orderBy('tb_units.sort_order', 'ASC')
                            ->findAll();
//echo $this->db->getLastQuery();exit;
        return $this->response->setJSON([
            'success' => true,
            'data' => $units
        ]);
    }

    /**
     * Get next sort order for course
     */
    private function getNextSortOrder($courseId)
    {
        $maxSort = $this->units->where('course_id', $courseId)
                           ->selectMax('sort_order')
                           ->first();

        return ($maxSort->sort_order ?? 0) + 1;
    }

    // ==================== Unit Items Management ====================

    /**
     * Get unit items for management
     */
    public function getUnitItems($unitId)
    {
        try {
            $items = $this->unitItems->getUnitItemsWithDetails($unitId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $items
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'فشل في جلب عناصر الوحدة: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Add new item to unit
     */
    public function addUnitItem()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $data = $this->request->getJSON(true);
            $unitId = $data['unit_id'] ?? null;
            $itemType = $data['item_type'] ?? null;

            if (!$unitId || !$itemType) {
                throw new \Exception('بيانات غير مكتملة');
            }

            $itemId = null;

            switch ($itemType) {
                case 'video':
                    $videoData = [
                        'video_id' => $data['video_id'] ?? '',
                        'video_title' => $data['video_title'] ?? $data['title'] ?? '',
                        'video_duration' => $data['video_duration'] ?? $data['duration'] ?? '',
                        'collection_id' => $data['collection_id'] ?? '',
                        'video_library_id' => $data['video_library_id'] ?? '',
                        'title' => $data['title'] ?? '',
                        'duration' => $data['duration'] ?? '',
                        'description' => $data['description'] ?? ''
                    ];
                    $itemId = $this->unitItems->createVideoItem($unitId, $videoData, $data['sort_order'] ?? null);
                    break;

                case 'quiz':
                    $quizId = $data['quiz_id'] ?? null;
                    if (!$quizId) {
                        throw new \Exception('معرف الاختبار مطلوب');
                    }
                    $itemId = $this->unitItems->createQuizItem($unitId, $quizId, $data['title'] ?? null, $data['sort_order'] ?? null);
                    break;

                case 'page':
                    $pageId = $data['page_id'] ?? null;
                    if (!$pageId) {
                        throw new \Exception('معرف الصفحة مطلوب');
                    }
                    $itemId = $this->unitItems->createPageItem($unitId, $pageId, $data['title'] ?? null, $data['sort_order'] ?? null);
                    break;

                default:
                    throw new \Exception('نوع العنصر غير صحيح');
            }

            if ($itemId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'تم إضافة العنصر بنجاح',
                    'item_id' => $itemId
                ]);
            } else {
                throw new \Exception('فشل في إضافة العنصر');
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update item sort order
     */
    public function updateItemOrder()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $data = $this->request->getJSON(true);
            $items = $data['items'] ?? [];

            if (empty($items)) {
                throw new \Exception('لا توجد عناصر للتحديث');
            }

            $success = $this->unitItems->updateSortOrders($items);

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'تم تحديث ترتيب العناصر بنجاح'
                ]);
            } else {
                throw new \Exception('فشل في تحديث ترتيب العناصر');
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle item active status
     */
    public function toggleItemStatus($itemId)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $success = $this->unitItems->toggleActive($itemId);

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'تم تحديث حالة العنصر بنجاح'
                ]);
            } else {
                throw new \Exception('فشل في تحديث حالة العنصر');
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete unit item
     */
    public function deleteUnitItem($itemId)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $success = $this->unitItems->delete($itemId);

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'تم حذف العنصر بنجاح'
                ]);
            } else {
                throw new \Exception('فشل في حذف العنصر');
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Save unit items from add form
     */
    private function saveUnitItems($unitId, $items)
    {
        if (empty($items)) {
            return;
        }

        foreach ($items as $item) {
            $itemData = [
                'unit_id' => $unitId,
                'item_type' => $item['item_type'],
                'item_id' => $item['item_id'] ?? '',
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? null,
                'sort_order' => $item['sort_order'] ?? 1,
                'is_active' => $item['is_active'] ?? 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            switch ($item['item_type']) {
                case 'video':
                    // Store video-specific data in existing columns for compatibility
                    $itemData['video_id'] = $item['video_id'] ?? '';
                    $itemData['video_title'] = $item['video_title'] ?? $item['title'] ?? '';
                    $itemData['video_duration'] = $item['duration'] ?? $item['video_duration'] ?? '';
                    $itemData['video_thumbnail'] = $item['video_thumbnail'] ?? $item['thumbnail'] ?? '';
                    $itemData['collection_id'] = $item['collection_id'] ?? '';

                    // Also store in metadata for enhanced functionality
                    $itemData['metadata'] = json_encode([
                        'video_id' => $item['video_id'] ?? '',
                        'video_title' => $item['video_title'] ?? $item['title'] ?? '',
                        'video_duration' => $item['duration'] ?? $item['video_duration'] ?? '',
                        'video_thumbnail' => $item['video_thumbnail'] ?? $item['thumbnail'] ?? '',
                        'collection_id' => $item['collection_id'] ?? '',
                        'video_library_id' => $item['video_library_id'] ?? '',
                        'file_size' => $item['file_size'] ?? null,
                        'video_quality' => $item['video_quality'] ?? null
                    ]);
                    break;

                case 'quiz':
                    $itemData['metadata'] = json_encode([
                        'quiz_id' => $item['quiz_id'] ?? null
                    ]);
                    break;

                case 'page':
                    $itemData['metadata'] = json_encode([
                        'page_id' => $item['page_id'] ?? null
                    ]);
                    break;
            }

            $result = $this->unitItems->insert($itemData);

            if (!$result) {
                log_message('error', 'Failed to insert unit item');
            }
        }
    }

    /**
     * Fetch video data from bunny.net (for add form)
     */
    public function fetchVideoData()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $videoId = $this->request->getPost('video_id');

            if (!$videoId) {
                throw new \Exception('معرف الفيديو مطلوب');
            }

            // Validate video ID format
            if (!BunnyNetService::isValidVideoId($videoId)) {
                throw new \Exception('معرف الفيديو غير صحيح');
            }

            // Fetch video data from Bunny.net
            $videoData = $this->bunnyNetService->getVideoData($videoId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $videoData
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get available quizzes for course (for add form)
     */
    public function getAvailableQuizzes($courseId)
    {
        try {
            $quizzes = $this->quizzesModel->where('course_id', $courseId)
                                        ->where('active', 1)
                                        ->select('id, quiz_title as title')
                                        ->orderBy('quiz_title', 'ASC')
                                        ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'quizzes' => $quizzes
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'فشل في جلب الاختبارات: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get available pages (for add form)
     */
    public function getAvailablePages()
    {
        try {
            $pages = $this->pagesModel->where('active', 1)
                                    ->select('id, title')
                                    ->orderBy('title', 'ASC')
                                    ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'pages' => $pages
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'فشل في جلب الصفحات: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get bunny.net video data
     */
    public function getBunnyVideoData()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $videoId = $this->request->getPost('video_id');

            if (!$videoId) {
                throw new \Exception('معرف الفيديو مطلوب');
            }

            // Validate video ID format
            if (!BunnyNetService::isValidVideoId($videoId)) {
                throw new \Exception('معرف الفيديو غير صحيح');
            }

            // Fetch video data from Bunny.net
            $videoData = $this->bunnyNetService->getVideoData($videoId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $videoData
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get available quizzes for course
     */
    public function getCourseQuizzes($courseId)
    {
        try {
            $quizzes = $this->quizzesModel->where('course_id', $courseId)
                                        ->where('active', 1)
                                        ->orderBy('quiz_title', 'ASC')
                                        ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $quizzes
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'فشل في جلب الاختبارات: ' . $e->getMessage()
            ]);
        }
    }

}
