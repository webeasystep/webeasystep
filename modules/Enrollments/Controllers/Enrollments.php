<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use Modules\Coupons\Models\CouponsModel;
use Modules\Courses\Models\CoursesModel;
use Modules\Enrollments\Models\CourseEnrollmentsModel;
use Modules\Users\Models\UsersModel;

class Enrollments extends BaseController
{
    protected CourseEnrollmentsModel $courseEnrollmentsModel;
    protected CouponsModel $couponsModel;
    protected CoursesModel $coursesModel;
    protected UsersModel $usersModel;

    public function __construct()
    {
        $this->courseEnrollmentsModel = new CourseEnrollmentsModel();
        $this->couponsModel = new CouponsModel();
        $this->coursesModel = new CoursesModel();
        $this->usersModel = new UsersModel();
    }


    /**
     * Display user's course purchases
     */
    public function myCourses()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->user()->id;
        $enrollments = $this->courseEnrollmentsModel->getUserEnrollments($userId);

        // Calculate progress for each enrollment
        $progressModel = new \Modules\Progress\Models\UserUnitProgressModel();
        
        foreach ($enrollments as $enrollment) {
            $enrollment->progress = 0;
            $enrollment->completed_units = 0;
            // Calculate total units dynamically
            $enrollment->total_units = $this->coursesModel->getUnitCount($enrollment->course_id);
            $enrollment->remaining_units = $enrollment->total_units;

            if ($enrollment->status === 'approved') {
                $enrollment->progress = $progressModel->getCourseCompletionPercentage($userId, $enrollment->course_id);
                
                // Get completed units count
                $enrollment->completed_units = $this->db->table('tb_user_item_progress')
                                          ->select('tb_user_item_progress.unit_id')
                                          ->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id')
                                          ->where('tb_user_item_progress.user_id', $userId)
                                          ->where('tb_units.course_id', $enrollment->course_id)
                                          ->where('tb_user_item_progress.is_completed', 1)
                                          ->groupBy('tb_user_item_progress.unit_id')
                                          ->countAllResults();

                $enrollment->remaining_units = max(0, $enrollment->total_units - $enrollment->completed_units);
            }
        }

        $data = [
            'title' => 'مقرراتي',
            'enrollments' => $enrollments
        ];

        return view('site/my_courses', $data);
    }

    /**
     * Handle course purchase - redirect to checkout
     */
    public function purchaseCourse($courseId = null)
    {
        if (!auth()->loggedIn()) {
            $this->show_msg('danger', 'error', "يرجى تسجيل الدخول أولاً");
            return redirect()->to('/login');
        }

        if (!$courseId) {
            $courseId = $this->request->getGet('course_id');
        }

        if (!$courseId) {
            return redirect()->to('/')->with('error', 'يرجى اختيار مقرر');
        }

        // Store in session and redirect to checkout
        session()->set('selected_course', $courseId);
        return redirect()->to('/enrollments/course-checkout');
    }

    /**
     * Course checkout page
     */
    public function courseCheckout()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->user()->id;
        $courseId = session()->get('selected_course');

        if (!$courseId) {
            return redirect()->to('/')->with('error', 'يرجى اختيار مقرر');
        }

        // Check if already enrolled
        if ($this->courseEnrollmentsModel->isUserEnrolled($userId, $courseId, false)) {
            session()->remove('selected_course');
            return redirect()->to('/enrollments/my-courses')->with('error', 'أنت مشترك بالفعل في هذا المقرر');
        }

        $course = $this->coursesModel->find($courseId);

        if (!$course) {
            session()->remove('selected_course');
            return redirect()->to('/')->with('error', 'الدورة غير موجودة');
        }

        // Handle POST - process purchase
        if ($this->request->is('post')) {
            return $this->processCourseCheckout($userId, $course);
        }

        // A course is only free if the admin explicitly marked it as free with the is_free flag.
        // Ignore price to avoid false positives with unpopulated prices.
        $isFree = ($course->is_free == 1);

        $data = [
            'title' => 'إتمام شراء الدورة',
            'course' => $course,
            'is_free' => $isFree,
            'files' => []
        ];

        return view('site/course_checkout', $data);
    }

    /**
     * Validate a coupon against the selected course and current user.
     */
    public function validateCoupon()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'يرجى تسجيل الدخول أولاً.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $userId = auth()->user()->id;
        $courseId = (int) ($this->request->getPost('course_id') ?? session()->get('selected_course'));
        $couponCode = trim((string) $this->request->getPost('coupon_code'));

        if (!$courseId || $couponCode === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'يرجى إدخال كود كوبون صالح.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $course = $this->coursesModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'الكورس غير موجود.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $coupon = $this->couponsModel->getValidCouponByCode($couponCode, $courseId);
        if (!$coupon) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Coupons.invalid_coupon'),
                'csrf_hash' => csrf_hash(),
            ]);
        }

        if (!$this->couponsModel->isAvailableForUser($coupon, (int) $userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Coupons.coupon_usage_limit_per_account_exceeded'),
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $originalAmount = (float) ($course->course_price ?? 0);
        $discountAmount = $this->couponsModel->calculateDiscountAmount($originalAmount, $coupon);
        $finalAmount = max(0, $originalAmount - $discountAmount);

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Coupons.coupon_applied'),
            'coupon_code' => $coupon->coupon_code,
            'original_amount' => number_format($originalAmount, 2, '.', ''),
            'discount_amount' => number_format($discountAmount, 2, '.', ''),
            'final_amount' => number_format($finalAmount, 2, '.', ''),
            'is_fully_discounted' => $finalAmount <= 0,
            'csrf_hash' => csrf_hash(),
        ]);
    }

    /**
     * Process course enrollment
     */
    private function processCourseCheckout($userId, $course)
    {
        $paymentMethod = $this->request->getPost('payment_method') ?? 'free';
        $couponCode = trim((string) ($this->request->getPost('coupon_code') ?? ''));
        // A course is only free if the admin explicitly marked it as free with the is_free flag.
        $isFree = ($course->is_free == 1);

        $coupon = null;
        $couponDiscountAmount = 0.0;
        $finalAmount = (float) ($course->course_price ?? 0);

        if ($couponCode !== '') {
            $coupon = $this->couponsModel->getValidCouponByCode($couponCode, (int) $course->id);

            if (!$coupon) {
                return redirect()->back()->withInput()->with('error', lang('Coupons.invalid_coupon'));
            }

            if (!$this->couponsModel->isAvailableForUser($coupon, (int) $userId)) {
                return redirect()->back()->withInput()->with('error', lang('Coupons.coupon_usage_limit_per_account_exceeded'));
            }

            $couponDiscountAmount = $this->couponsModel->calculateDiscountAmount((float) $course->course_price, $coupon);
            $finalAmount = max(0, (float) $course->course_price - $couponDiscountAmount);
        }

        // Auto-approve free courses
        if ($isFree || $paymentMethod === 'free' || $finalAmount <= 0) {
            $enrollmentId = $this->courseEnrollmentsModel->createEnrollment($userId, $course->id, [
                'paid_amount' => 0,
                'coupon_id' => $coupon->id ?? null,
                'coupon_code' => $coupon->coupon_code ?? null,
                'coupon_discount_amount' => $couponDiscountAmount,
                'payment_method' => 'free',
                'auto_approve' => true
            ]);

            if ($enrollmentId) {
                if ($coupon) {
                    $this->couponsModel->incrementUsage((int) $coupon->id);
                }
                $this->sendApprovalEmail($enrollmentId);
                session()->remove('selected_course');
                return redirect()->to('/courses/course_view/' . $course->slug)
                    ->with('success', 'تم تسجيلك في الدورة بنجاح! يمكنك الآن الوصول إلى المحتوى.');
            }
        }

        // Handle payment proof upload
        $paymentProofPath = null;
        $proofFile = $this->request->getFile('payment_proof');
        if ($proofFile && $proofFile->isValid() && !$proofFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'enrollments';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            $randomName = $proofFile->getRandomName();
            $proofFile->move($uploadPath, $randomName);
            $paymentProofPath = 'uploads/enrollments/' . $randomName;
        }

        // Handle paid course
        $enrollmentId = $this->courseEnrollmentsModel->createEnrollment($userId, $course->id, [
            'paid_amount' => $finalAmount,
            'coupon_id' => $coupon->id ?? null,
            'coupon_code' => $coupon->coupon_code ?? null,
            'coupon_discount_amount' => $couponDiscountAmount,
            'payment_method' => $paymentMethod,
            'payment_proof' => $paymentProofPath,
            'auto_approve' => false
        ]);

        if (!$enrollmentId) {
            return redirect()->back()->with('error', 'فشل في حفظ طلب الشراء');
        }

        $this->sendPendingEnrollmentEmails($enrollmentId);

        session()->remove('selected_course');
        return redirect()->to('/enrollments/my-courses')
            ->with('success', 'تم إرسال طلب الشراء بنجاح. سيتم مراجعته من قبل الإدارة وتفعيل اشتراكك بعد التحقق من الدفع.');
    }

    /**
     * Send enrollment request emails to the customer and the admin.
     */
    private function sendPendingEnrollmentEmails(int $enrollmentId): void
    {
        $enrollment = $this->courseEnrollmentsModel
            ->select('tb_course_enrollments.*, tb_courses.course_title, tb_courses.slug, users.full_name, auth_identities.secret as email')
            ->join('tb_courses', 'tb_courses.id = tb_course_enrollments.course_id')
            ->join('users', 'users.id = tb_course_enrollments.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->find($enrollmentId);

        if (!$enrollment || empty($enrollment->email)) {
            log_message('error', 'Could not send pending enrollment emails for enrollment ID: ' . $enrollmentId . ' - User or email not found.');
            return;
        }

        $adminEmail = 'webeasystep@gmail.com';

        $email = \Config\Services::email();
        $email->setMailType('html');

        $commonData = [
            'full_name'      => $enrollment->full_name,
            'course_title'   => $enrollment->course_title,
            'paid_amount'    => $enrollment->paid_amount,
            'payment_method' => $enrollment->payment_method,
            'submitted_at'   => $enrollment->created_at ?? date('Y-m-d H:i:s'),
        ];

        $customerMessage = MainView('Modules\Enrollments\Views\Site\emails\course_request_received', $commonData);
        $email->setTo($enrollment->email);
        $email->setSubject('تم استلام طلب اشتراكك في دورة: ' . $enrollment->course_title);
        $email->setMessage($customerMessage);

        if (!$email->send()) {
            log_message('error', 'Failed to send enrollment confirmation email: ' . $email->printDebugger(['headers']));
        }

        $email->clear(true);
        $email->setMailType('html');

        $adminMessage = MainView('Modules\Enrollments\Views\Site\emails\course_request_admin_notification', $commonData + [
            'customer_email' => $enrollment->email,
            'review_url'     => base_url('dt_admin/enrollments/courses/show/' . $enrollmentId),
        ]);

        $email->setTo($adminEmail);
        $email->setSubject('طلب اشتراك جديد في دورة: ' . $enrollment->course_title);
        $email->setMessage($adminMessage);

        if (!$email->send()) {
            log_message('error', 'Failed to send admin enrollment notification email: ' . $email->printDebugger(['headers']));
        }
    }

    /**
     * Send course approval email after automatic activation flows.
     */
    private function sendApprovalEmail(int $enrollmentId): bool
    {
        $enrollment = $this->courseEnrollmentsModel
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
        $email->setMailType('html');

        $courseUrl = base_url('courses/course_view/' . $enrollment->slug);
        $message = MainView('Modules\Enrollments\Views\Site\emails\course_approved', [
            'full_name'    => $enrollment->full_name,
            'course_title' => $enrollment->course_title,
            'course_url'   => $courseUrl,
        ]);

        $email->setMessage($message);

        $success = $email->send();

        if (!$success) {
            log_message('error', 'Failed to send auto-approval email: ' . $email->printDebugger(['headers']));
        }

        return $success;
    }
}
