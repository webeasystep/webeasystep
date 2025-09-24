<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\FireUploader;
use Modules\Courses\Models\CoursesModel;
use Modules\Enrollments\Models\EnrollmentsModel;
use Modules\Enrollments\Models\UnitEnrollmentsModel;
use Modules\Units\Models\UnitsModel;
use Modules\Users\Models\UsersModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Enrollments extends BaseController
{
    protected EnrollmentsModel $enrollmentsModel;
    protected UnitEnrollmentsModel $unitEnrollmentsModel;
    protected CoursesModel     $coursesModel;
    protected UnitsModel       $unitsModel;
    protected UsersModel       $usersModel;
    protected FireUploader     $fireUploader;

    /**
     * Validation rules for creating a new user (when not logged in & buying a paid course).
     */
    private array $rules = [
        'name'            => 'required|min_length[3]',
        'email'           => 'required|valid_email',
        'country'         => 'required',
        'phone'           => 'required',
        'password'        => 'required|min_length[5]',
        'confirmPassword' => 'required|matches[password]',
    ];

    public function __construct()
    {
        $this->enrollmentsModel = new EnrollmentsModel();
        $this->unitEnrollmentsModel = new UnitEnrollmentsModel();
        $this->coursesModel     = new CoursesModel();
        $this->unitsModel       = new UnitsModel();
        $this->usersModel       = new UsersModel();
        $this->fireUploader     = new FireUploader();
    }

    /**
     * Example index method if you want to list enrollments.
     */
    public function index()
    {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Enrollments index working']);
    }

    /**
     * Test method to verify controller access
     */
    public function test()
    {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Enrollments controller test method working']);
    }

    /**
     * Display user's unit purchases
     */
    public function myPurchases()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->user()->id;
        $purchases = $this->unitEnrollmentsModel->getUserEnrollments($userId);

        $data = [
            'title' => 'مشترياتي',
            'purchases' => $purchases
        ];

        return view('site/my_purchases', $data);
    }


    /**
     * Display units available for purchase
     */
    public function unitsShop()
    {
        $data = [
            'title' => 'شراء الوحدات الدراسية',
            'units' => $this->unitsModel->getPurchasableUnits()
        ];

        return view('Site/units_shop', $data);
    }

    /**
     * Handle unit purchase checkout
     */
    public function purchaseUnits()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        if ($this->request->is('post')) {
            return $this->processPurchaseUnits($userId);
        }

        $unitIds = $this->request->getGet('units');
        if (!$unitIds) {
            return redirect()->to('/enrollments/units-shop')->with('error', 'يرجى اختيار وحدة واحدة على الأقل');
        }

        $unitIds = explode(',', $unitIds);
        $selectedUnits = $this->unitsModel->whereIn('id', $unitIds)->findAll();

        if (empty($selectedUnits)) {
            return redirect()->to('/enrollments/units-shop')->with('error', 'الوحدات المحددة غير متاحة');
        }

        $totalPrice = 0;
        foreach ($selectedUnits as $unit) {
            $totalPrice += $unit->unit_price ?? 0;
        }

        $data = [
            'title' => 'إتمام شراء الوحدات',
            'selected_units' => $selectedUnits,
            'total_price' => $totalPrice,
            'unit_ids' => $unitIds,
            'files' => [] // Initialize empty files array for FireUploader
        ];

        return view('site/purchase_units', $data);
    }

    /**
     * Complete enrollment for free units or process paid enrollment
     */
    public function completeEnrollment()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();

        // Get unit_ids from POST data
        $unitIds = $this->request->getPost('unit_ids');

        if (empty($unitIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No units selected for enrollment'
            ]);
        }

        // Convert comma-separated string to array
        $unitIdsArray = is_string($unitIds) ? explode(',', $unitIds) : $unitIds;
        $unitIdsArray = array_map('trim', $unitIdsArray);

        // Check for existing enrollment with the same units
        $unitIdsJson = json_encode($unitIdsArray);
        
        // First check for exact match
        $existingEnrollment = $this->enrollmentsModel
            ->where('user_id', $userId)
            ->where('unit_ids', $unitIdsJson)
            ->where('status !=', 'cancelled')
            ->first();

        // If no exact match, check for any overlapping units
        if (!$existingEnrollment) {
            $allEnrollments = $this->enrollmentsModel
                ->where('user_id', $userId)
                ->where('status !=', 'cancelled')
                ->findAll();
                
            foreach ($allEnrollments as $enrollment) {
                $existingUnits = json_decode($enrollment->unit_ids, true);
                if (is_array($existingUnits)) {
                    $overlap = array_intersect($unitIdsArray, $existingUnits);
                    if (!empty($overlap)) {
                        $existingEnrollment = $enrollment;
                        break;
                    }
                }
            }
        }
        
        if ($existingEnrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You have already enrolled in some of these units',
                'existing_enrollment' => $existingEnrollment
            ]);
        }

        // Create new enrollment
        $enrollmentData = [
            'user_id' => $userId,
            'unit_ids' => json_encode($unitIdsArray),
            'status' => 'pending',
            'enrolled_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $enrollmentId = $this->enrollmentsModel->insert($enrollmentData);

        if ($enrollmentId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment completed successfully',
                'enrollment_id' => $enrollmentId
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to complete enrollment'
            ]);
        }
    }

    /**
     * Process unit purchase with payment proof
     */
    private function processPurchaseUnits($userId)
    {
        $unitIds = $this->request->getPost('unit_ids');
        $paymentMethod = $this->request->getPost('payment_method') ?? 'vodafone_cash';
        
        // Debug logging
        log_message('debug', 'ProcessPurchaseUnits - User ID: ' . $userId);
        log_message('debug', 'ProcessPurchaseUnits - Unit IDs: ' . json_encode($unitIds));
        log_message('debug', 'ProcessPurchaseUnits - Payment Method: ' . $paymentMethod);
        log_message('debug', 'ProcessPurchaseUnits - All POST data: ' . json_encode($this->request->getPost()));

        if (empty($unitIds)) {
            return redirect()->back()->with('error', 'يرجى اختيار وحدة واحدة على الأقل')->withInput();
        }

        // Check for duplicate enrollments
        $duplicateUnits = $this->unitEnrollmentsModel->checkDuplicateEnrollments($userId, $unitIds);
        if (!empty($duplicateUnits)) {
            return redirect()->back()->with('error', 'لقد تم الاشتراك في هذه الوحدة من قبل')->withInput();
        }

        // Validate payment proof using FireUploader
        $paymentProofFiles = $this->request->getPost('payment_proof');
        if (empty($paymentProofFiles)) {
            return redirect()->back()->with('error', 'يرجى إرفاق إثبات الدفع')->withInput();
        }

        // Parse the payment proof JSON data
        $paymentProofData = null;
        if (is_array($paymentProofFiles) && !empty($paymentProofFiles[0])) {
            $paymentProofData = json_decode($paymentProofFiles[0], true);
            log_message('debug', 'Payment proof data: ' . json_encode($paymentProofData));
        }
        
        if (!$paymentProofData || !isset($paymentProofData['original_name'])) {
            return redirect()->back()->with('error', 'يرجى إرفاق إثبات الدفع صحيح')->withInput();
        }

        // Calculate total price
        $units = $this->unitsModel->whereIn('id', $unitIds)->findAll();
        $totalPrice = 0;
        foreach ($units as $unit) {
            $totalPrice += $unit->price ?? 0;
        }
        
        log_message('debug', 'Units found: ' . count($units));
        log_message('debug', 'Total price calculated: ' . $totalPrice);

        if ($totalPrice <= 0) {
            return redirect()->back()->with('error', 'خطأ في حساب المبلغ الإجمالي')->withInput();
        }

        // Create enrollment request
        $enrollmentData = [
            'user_id' => $userId,
            'unit_ids' => json_encode($unitIds),
            'total_amount' => $totalPrice,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        log_message('debug', 'Enrollment data to insert: ' . json_encode($enrollmentData));
        
        $enrollmentId = $this->unitEnrollmentsModel->insert($enrollmentData);
        
        log_message('debug', 'Enrollment ID after insert: ' . $enrollmentId);
        log_message('debug', 'Database errors: ' . json_encode($this->unitEnrollmentsModel->errors()));

        if (!$enrollmentId) {
            log_message('error', 'Failed to insert enrollment data');
            return redirect()->back()->with('error', 'فشل في حفظ طلب الشراء')->withInput();
        }

        // Upload payment proof using FireUploader
        try {
            $this->fireUploader->upload_photos($this->unitEnrollmentsModel, 'payment_proof', $enrollmentId);
            log_message('debug', 'Payment proof uploaded successfully for enrollment ID: ' . $enrollmentId);
        } catch (\Exception $e) {
            log_message('error', 'Payment proof upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'فشل في رفع إثبات الدفع: ' . $e->getMessage())->withInput();
        }

        return redirect()->to('/enrollments/my-purchases')
                        ->with('success', 'تم إرسال طلب الشراء بنجاح. سيتم مراجعته من قبل الإدارة وتفعيل الوحدات عند الموافقة. يرجى انتظار التفعيل.');
    }

}
