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
    public function index(): string
    {
        $data = [
            'title'       => 'Enrollments List',
            'enrollments' => $this->enrollmentsModel
                ->where('status', 'completed')
                ->paginate(10),
            'pager' => $this->enrollmentsModel->pager,
        ];

        return view('site/complete_enrollment', $data);
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
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        $unitIds = $this->request->getPost('unit_ids');
        if (empty($unitIds)) {
            return redirect()->back()->with('error', 'يرجى اختيار وحدة واحدة على الأقل');
        }

        // Parse unit IDs if they come as JSON string
        if (is_string($unitIds)) {
            $unitIds = json_decode($unitIds, true);
        }

        if (!is_array($unitIds)) {
            return redirect()->back()->with('error', 'خطأ في معرفات الوحدات');
        }

        // Get selected units
        $units = $this->unitsModel->whereIn('id', $unitIds)->findAll();
        if (empty($units)) {
            return redirect()->back()->with('error', 'الوحدات المحددة غير متاحة');
        }

        // Check if all units are free
        $allFree = true;
        $totalPrice = 0;
        foreach ($units as $unit) {
            if (!$unit->is_free) {
                $allFree = false;
            }
            $totalPrice += $unit->price ?? 0;
        }

        if ($allFree) {
            // Handle free unit enrollment - grant immediate access
            $enrollmentData = [
                'user_id' => $userId,
                'unit_ids' => json_encode($unitIds),
                'total_amount' => 0,
                'payment_method' => 'free',
                'status' => 'approved',
                'processed_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $enrollmentId = $this->unitEnrollmentsModel->insert($enrollmentData);
            
            if ($enrollmentId) {
                // Grant access to free units
                if (class_exists('\Modules\Units\Models\UnitPurchasesModel')) {
                    $unitPurchasesModel = new \Modules\Units\Models\UnitPurchasesModel();
                    
                    foreach ($unitIds as $unitId) {
                        $purchaseData = [
                            'user_id' => $userId,
                            'unit_id' => $unitId,
                            'payment_attachment_id' => $enrollmentId,
                            'price_paid' => 0,
                            'access_granted' => 1,
                            'access_expires_at' => null
                        ];
                        
                        $unitPurchasesModel->insertPurchase($purchaseData);
                    }
                }

                // Get the first unit's course to redirect to course view
                $firstUnit = $units[0];
                $course = $this->coursesModel->find($firstUnit->course_id);
                
                if ($course) {
                    // Redirect to course view as requested
                    return redirect()->to('/courses/view/' . $course->id)
                                   ->with('success', 'تم تسجيلك في الوحدات المجانية بنجاح! يمكنك الآن الوصول إلى المحتوى.');
                } else {
                    return redirect()->to('/enrollments/my-purchases')
                                   ->with('success', 'تم تسجيلك في الوحدات المجانية بنجاح!');
                }
            } else {
                return redirect()->back()->with('error', 'فشل في تسجيل الاشتراك المجاني');
            }
        } else {
            // Handle paid enrollment - redirect to payment process
            return redirect()->to('/enrollments/purchase-units?units=' . implode(',', $unitIds))
                           ->with('info', 'يرجى إتمام عملية الدفع للوحدات المدفوعة');
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
