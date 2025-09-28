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

        return view('site/purchase_units', $data);
    }

    /**
     * Handle unit selection and redirect to checkout
     */
    public function purchaseUnits()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        // Handle unit selection from GET parameters
        $unitIds = $this->request->getGet('units');
        if ($unitIds) {
            // Store selected units in session
            $unitIds = explode(',', $unitIds);
            session()->set('selected_units', $unitIds);

            // Redirect to checkout
            return redirect()->to('/enrollments/checkout');
        }

        // If no units specified, redirect to units shop
        return redirect()->to('/enrollments/units-shop')->with('error', 'يرجى اختيار وحدة واحدة على الأقل');
    }

    /**
     * Handle checkout process
     */
    public function checkout()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        if ($this->request->is('post')) {
            return $this->processCheckout($userId);
        }

        // Get selected units from session
        $unitIds = session()->get('selected_units');
        if (!$unitIds) {
            return redirect()->to('/enrollments/units-shop')->with('error', 'يرجى اختيار وحدة واحدة على الأقل');
        }

        $selectedUnits = $this->unitsModel->whereIn('id', $unitIds)->findAll();

        if (empty($selectedUnits)) {
            session()->remove('selected_units');
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

        // Check for existing enrollments for these units
        $duplicateEnrollments = $this->unitEnrollmentsModel->checkDuplicateEnrollmentsForUnits($userId, $unitIdsArray);

        if (!empty($duplicateEnrollments)) {
            $duplicateUnitIds = array_column($duplicateEnrollments, 'unit_id');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You have already enrolled in some of these units',
                'duplicate_units' => $duplicateUnitIds
            ]);
        }

        // Create individual enrollments for each unit
        $enrollmentIds = $this->unitEnrollmentsModel->createMultipleUnitEnrollments([
            'user_id' => $userId,
            'unit_ids' => $unitIdsArray,
            'total_amount' => 0, // Will be calculated per unit
            'status' => 'pending'
        ]);

        if ($enrollmentIds) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment completed successfully',
                'enrollment_ids' => $enrollmentIds
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to complete enrollment'
            ]);
        }
    }

    /**
     * Process checkout with payment proof
     */
    private function processCheckout($userId)
    {
        // Get selected units from session
        $unitIds = session()->get('selected_units');
        $paymentMethod = $this->request->getPost('payment_method') ?? 'vodafone_cash';

        // Debug logging
        log_message('debug', 'ProcessCheckout - User ID: ' . $userId);
        log_message('debug', 'ProcessCheckout - Unit IDs from session: ' . json_encode($unitIds));
        log_message('debug', 'ProcessCheckout - Payment Method: ' . $paymentMethod);
        log_message('debug', 'ProcessCheckout - All POST data: ' . json_encode($this->request->getPost()));

        if (empty($unitIds)) {
            session()->remove('selected_units');
            return redirect()->to('/enrollments/units-shop')->with('error', 'يرجى اختيار وحدة واحدة على الأقل');
        }

        // Check for duplicate enrollments
        $duplicateEnrollments = $this->unitEnrollmentsModel->checkDuplicateEnrollmentsForUnits($userId, $unitIds);
        if (!empty($duplicateEnrollments)) {
            $duplicateUnitIds = array_column($duplicateEnrollments, 'unit_id');
            session()->remove('selected_units');
            return redirect()->back()->with('error', 'لقد تم الاشتراك في بعض هذه الوحدات من قبل: ' . implode(', ', $duplicateUnitIds))->withInput();
        }

        // Calculate total price and check if all units are free
        $units = $this->unitsModel->whereIn('id', $unitIds)->findAll();
        $totalPrice = 0;
        $allUnitsFree = true;

        foreach ($units as $unit) {
            $totalPrice += $unit->price ?? 0;
            if (!$unit->is_free) {
                $allUnitsFree = false;
            }
        }

        log_message('debug', 'Units found: ' . count($units));
        log_message('debug', 'Total price calculated: ' . $totalPrice);
        log_message('debug', 'All units free: ' . ($allUnitsFree ? 'Yes' : 'No'));

        // Handle free units - auto approve and redirect to course
        if ($allUnitsFree) {
            // Create individual enrollment requests with approved status
            $enrollmentIds = $this->unitEnrollmentsModel->createMultipleUnitEnrollments([
                'user_id' => $userId,
                'unit_ids' => $unitIds,
                'total_amount' => 0,
                'payment_method' => $paymentMethod,
                'status' => 'approved'
            ]);

            log_message('debug', 'Free units enrollment IDs: ' . json_encode($enrollmentIds));

            if (!$enrollmentIds) {
                log_message('error', 'Failed to insert free units enrollment data');
                session()->remove('selected_units');
                return redirect()->back()->with('error', 'فشل في تسجيل الاشتراك المجاني')->withInput();
            }

            // Clear session after successful enrollment
            session()->remove('selected_units');

            // Get the course ID from the first unit to redirect to course page
            $firstUnit = $units[0];
            $courseId = $firstUnit->course_id;

            // Get course slug for proper redirect
            $coursesModel = new \Modules\Courses\Models\CoursesModel();
            $course = $coursesModel->getCourseById($courseId);

            if (!$course) {
                log_message('error', 'Course not found for ID: ' . $courseId);
                session()->remove('selected_units');
                return redirect()->to('/enrollments/units-shop')->with('error', 'خطأ في العثور على الكورس');
            }

            log_message('debug', 'Free enrollment successful, redirecting to course: ' . $course->slug);

            return redirect()->to('/courses/course_view/' . $course->slug)
                            ->with('success', 'تم تسجيلك في الوحدات المجانية بنجاح! يمكنك الآن الوصول إلى المحتوى.');
        }

        // Handle paid units - existing logic
        if ($totalPrice <= 0) {
            session()->remove('selected_units');
            return redirect()->back()->with('error', 'خطأ في حساب المبلغ الإجمالي')->withInput();
        }

        // Create individual enrollment requests for paid units
        $enrollmentIds = $this->unitEnrollmentsModel->createMultipleUnitEnrollments([
            'user_id' => $userId,
            'unit_ids' => $unitIds,
            'total_amount' => $totalPrice,
            'payment_method' => $paymentMethod,
            'status' => 'pending'
        ]);

        log_message('debug', 'Paid units enrollment IDs: ' . json_encode($enrollmentIds));
        log_message('debug', 'Database errors: ' . json_encode($this->unitEnrollmentsModel->errors()));

        if (!$enrollmentIds) {
            log_message('error', 'Failed to insert enrollment data');
            session()->remove('selected_units');
            return redirect()->back()->with('error', 'فشل في حفظ طلب الشراء')->withInput();
        }

        // Clear session after successful enrollment
        session()->remove('selected_units');

        return redirect()->to('/enrollments/my-purchases')
                        ->with('success', 'تم إرسال طلب الشراء بنجاح. سيتم مراجعته من قبل الإدارة وتفعيل الوحدات عند الموافقة. يرجى انتظار التفعيل.');
    }

}
