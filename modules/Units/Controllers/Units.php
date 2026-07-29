<?php

namespace Modules\Units\Controllers;

use App\Controllers\BaseController;
use Modules\Units\Models\UnitsModel;
use Modules\Courses\Models\CoursesModel;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Units extends BaseController
{
    protected UnitsModel $unitsModel;
    protected CoursesModel $coursesModel;


    public function __construct()
    {
        $this->unitsModel = new UnitsModel();
        $this->coursesModel = new CoursesModel();

        helper(['text', 'url', 'form']);
    }

    /**
     * Show purchasable units for selection
     */
    public function shop()
    {
        $data['title'] = lang('Units.shop_units');
        $data['units'] = $this->unitsModel->getPurchasableUnits();
        
        return view('Site/units_shop', $data);
    }

    /**
     * Submit payment for selected units
     */
    public function submitPayment()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'سجّل دخولك أول، وبعدها تقدر تشتري الوحدات بكل سهولة.');
        }

        if ($this->request->is('post')) {
            $unitIds = $this->request->getPost('unit_ids');
            $paymentMethod = $this->request->getPost('payment_method');
            
            if (empty($unitIds)) {
                return redirect()->back()->with('error', 'اختر وحدة واحدة على الأقل، وبعدها نكمل الطلب معك.');
            }

            // Calculate total price
            $units = $this->unitsModel->whereIn('id', $unitIds)->findAll();
            $totalPrice = 0;
            foreach ($units as $unit) {
                $totalPrice += $unit->price ?? 0;
            }

            // Handle file upload
            $uploadedFile = $this->request->getFile('payment_attachment');
            if (!$uploadedFile->isValid()) {
                return redirect()->back()->with('error', 'ارفع إثبات دفع واضح وصحيح حتى نقدر نراجع طلبك بسرعة.');
            }

            $fileName = $uploadedFile->getRandomName();
            $uploadedFile->move(WRITEPATH . 'uploads/payments/', $fileName);

            // Save payment attachment
            $paymentData = [
                'user_id' => $userId,
                'unit_ids' => json_encode($unitIds),
                'total_price' => $totalPrice,
                'payment_attachment' => 'payments/' . $fileName,
                'payment_method' => $paymentMethod,
                'status' => 'pending'
            ];

            $paymentsModel = new \Modules\Units\Models\PaymentAttachmentsModel();
            if ($paymentsModel->insertPayment($paymentData)) {
                return redirect()->to('units/my-purchases')->with('success', 'Payment submitted successfully. Please wait for admin approval.');
            } else {
                return redirect()->back()->with('error', 'ما قدرنا نرسل طلب الدفع الآن. جرّب مرة ثانية، وإذا احتجت إحنا معك.');
            }
        }

        $data['title'] = lang('Units.submit_payment');
        $unitIds = $this->request->getGet('units');
        if ($unitIds) {
            $unitIds = explode(',', $unitIds);
            $data['selected_units'] = $this->unitsModel->whereIn('id', $unitIds)->findAll();
        } else {
            $data['selected_units'] = [];
        }
        
        return view('Site/payment_form', $data);
    }

    /**
     * View user's purchases and payment status
     */
    public function myPurchases()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'سجّل دخولك أول، وبعدها تشوف مشترياتك بكل وضوح.');
        }

        $data['title'] = lang('Units.my_purchases');
        
        $paymentsModel = new \Modules\Units\Models\PaymentAttachmentsModel();
        $purchasesModel = new \Modules\Units\Models\UnitPurchasesModel();
        
        $data['payments'] = $paymentsModel->getUserPayments($userId);
        $data['purchases'] = $purchasesModel->getUserPurchases($userId);
        
        return view('Site/my_purchases', $data);
    }

    /**
     * View individual unit with progress tracking
     */
    public function viewUnit(int $unitId)
    {
        $unit = $this->unitsModel->find($unitId);
        if (!$unit || !$unit->active) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Get course info
        $course = $this->coursesModel->find($unit->course_id);
        if (!$course) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Check enrollment
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return redirect()->to('login')->with('error', 'سجّل دخولك أول، وبعدها تقدر تدخل على محتوى المقرر.');
        }

        $enrollment = $this->coursesModel->getEnrollment($userId, $course->id);
        if (!$enrollment && !$unit->is_free) {
            return redirect()->to('courses')->with('error', 'لازم يكون عندك اشتراك في هذا المقرر أول، وبعدها يفتح لك المحتوى كامل.');
        }

        // Get navigation units
        $prevUnit = $this->unitsModel->getPreviousUnit($unitId, $unit->course_id);
        $nextUnit = $this->unitsModel->getNextUnit($unitId, $unit->course_id);

        // Check if unit is completed
        $isCompleted = false;
        if ($enrollment && class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            $unitProgress = $progressModel->getUserUnitProgress($userId, $unitId);
            $isCompleted = $unitProgress ? $unitProgress->is_completed : false;
        }

        // Get course completion percentage
        $courseCompletion = 0;
        if ($enrollment && class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            $courseCompletion = $progressModel->getCourseCompletionPercentage($userId, $course->id);
        }

        // Get unit quizzes
        $quizzes = [];
        if (class_exists('\Modules\Quizzes\Models\QuizzesModel')) {
            $quizzesModel = new \Modules\Quizzes\Models\QuizzesModel();
            $unitQuizzes = $this->db->table('tb_unit_quizzes')
                                   ->select('quiz_id')
                                   ->where('unit_id', $unitId)
                                   ->get()
                                   ->getResultArray();
            
            if (!empty($unitQuizzes)) {
                $quizIds = array_column($unitQuizzes, 'quiz_id');
                $quizzes = $quizzesModel->whereIn('id', $quizIds)
                                       ->where('active', 1)
                                       ->findAll();
            }
        }

        $data = [
            'title' => $unit->unit_name,
            'course' => $course,
            'section' => $section,
            'unit' => $unit,
            'prevUnit' => $prevUnit,
            'nextUnit' => $nextUnit,
            'isCompleted' => $isCompleted,
            'courseCompletion' => $courseCompletion,
            'quizzes' => $quizzes,
            'enrollment' => $enrollment
        ];

        return view('Site/unit_view', $data);
    }

    /**
     * Mark unit as complete (AJAX)
     */
    public function markComplete()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Not authenticated']);
        }

        $unitId = $this->request->getPost('unit_id');
        $courseId = $this->request->getPost('course_id');

        if (!$unitId || !$courseId) {
            return $this->response->setJSON(['error' => 'Missing required parameters']);
        }

        // Verify enrollment
        $enrollment = $this->coursesModel->getEnrollment($userId, $courseId);
        if (!$enrollment) {
            return $this->response->setJSON(['error' => 'Not enrolled in this course']);
        }

        // Mark unit as complete if Progress module exists
        if (class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            
            $progressData = [
                'user_id' => $userId,
                'unit_id' => $unitId,
                'course_id' => $courseId,
                'is_completed' => 1,
                'completion_date' => date('Y-m-d H:i:s'),
                'progress_percentage' => 100
            ];

            // Check if progress record exists
            $existingProgress = $progressModel->where('user_id', $userId)
                                             ->where('unit_id', $unitId)
                                             ->first();

            if ($existingProgress) {
                $progressModel->update($existingProgress->id, $progressData);
            } else {
                $progressModel->insert($progressData);
            }

            // Get updated course completion percentage
            $courseCompletion = $progressModel->getCourseCompletionPercentage($userId, $courseId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Unit marked as complete',
                'course_completion' => $courseCompletion
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Unit marked as complete']);
    }

    /**
     * Get unit progress (AJAX)
     */
    public function getProgress()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Not authenticated']);
        }

        $unitId = $this->request->getGet('unit_id');
        if (!$unitId) {
            return $this->response->setJSON(['error' => 'Unit ID required']);
        }

        $progress = ['is_completed' => false, 'progress_percentage' => 0];

        if (class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            $unitProgress = $progressModel->getUserUnitProgress($userId, $unitId);
            
            if ($unitProgress) {
                $progress = [
                    'is_completed' => (bool)$unitProgress->is_completed,
                    'progress_percentage' => $unitProgress->progress_percentage ?? 0,
                    'last_accessed' => $unitProgress->updated_at
                ];
            }
        }

        return $this->response->setJSON($progress);
    }

    /**
     * Update unit progress (AJAX)
     */
    public function updateProgress()
    {
        $userId = auth()->user()->id ?? null;
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Not authenticated']);
        }

        $unitId = $this->request->getPost('unit_id');
        $courseId = $this->request->getPost('course_id');
        $progressPercentage = $this->request->getPost('progress_percentage');

        if (!$unitId || !$courseId || $progressPercentage === null) {
            return $this->response->setJSON(['error' => 'Missing required parameters']);
        }

        // Verify enrollment
        $enrollment = $this->coursesModel->getEnrollment($userId, $courseId);
        if (!$enrollment) {
            return $this->response->setJSON(['error' => 'Not enrolled in this course']);
        }

        if (class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            
            $progressData = [
                'user_id' => $userId,
                'unit_id' => $unitId,
                'course_id' => $courseId,
                'progress_percentage' => min(100, max(0, (int)$progressPercentage)),
                'is_completed' => $progressPercentage >= 100 ? 1 : 0,
                'last_accessed' => date('Y-m-d H:i:s')
            ];

            if ($progressPercentage >= 100) {
                $progressData['completion_date'] = date('Y-m-d H:i:s');
            }

            // Check if progress record exists
            $existingProgress = $progressModel->where('user_id', $userId)
                                             ->where('unit_id', $unitId)
                                             ->first();

            if ($existingProgress) {
                $progressModel->update($existingProgress->id, $progressData);
            } else {
                $progressModel->insert($progressData);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Progress updated']);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Progress updated']);
    }

    /**
     * Get unit quizzes
     */
    public function getUnitQuizzes(int $unitId)
    {
        $quizzes = [];
        
        if (class_exists('\Modules\Quizzes\Models\QuizzesModel')) {
            // Get the unit's course_id first
            $unitsModel = new \Modules\Units\Models\UnitsModel();
            $unit = $unitsModel->find($unitId);
            
            if ($unit) {
                $quizzesModel = new \Modules\Quizzes\Models\QuizzesModel();
                $quizzes = $quizzesModel->where('course_id', $unit->course_id)
                                       ->where('active', 1)
                                       ->orderBy('created_at', 'ASC')
                                       ->findAll();
            }
        }

        return $this->response->setJSON($quizzes);
    }

    /**
     * Get section units for navigation
     */
    public function getSectionUnits(int $sectionId)
    {
        $units = $this->unitsModel->getUnitsBySection($sectionId);
        
        // Add completion status if user is logged in
        $userId = auth()->user()->id ?? null;
        if ($userId && class_exists('\Modules\Progress\Models\UserUnitProgressModel')) {
            $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
            
            foreach ($units as &$unit) {
                $progress = $progressModel->getUserUnitProgress($userId, $unit->id);
                $unit->is_completed = $progress ? (bool)$progress->is_completed : false;
                $unit->progress_percentage = $progress ? $progress->progress_percentage : 0;
            }
        }

        return $this->response->setJSON($units);
    }
}
