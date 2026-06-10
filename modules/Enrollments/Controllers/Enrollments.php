<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use Modules\Courses\Models\CoursesModel;
use Modules\Enrollments\Models\CourseEnrollmentsModel;
use Modules\Users\Models\UsersModel;

class Enrollments extends BaseController
{
    protected CourseEnrollmentsModel $courseEnrollmentsModel;
    protected CoursesModel $coursesModel;
    protected UsersModel $usersModel;

    public function __construct()
    {
        $this->courseEnrollmentsModel = new CourseEnrollmentsModel();
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
            'title' => 'دوراتي',
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
            return redirect()->to('/')->with('error', 'يرجى اختيار دورة');
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
            return redirect()->to('/')->with('error', 'يرجى اختيار دورة');
        }

        // Check if already enrolled
        if ($this->courseEnrollmentsModel->isUserEnrolled($userId, $courseId, false)) {
            session()->remove('selected_course');
            return redirect()->to('/enrollments/my-courses')->with('error', 'أنت مشترك بالفعل في هذه الدورة');
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
     * Process course enrollment
     */
    private function processCourseCheckout($userId, $course)
    {
        $paymentMethod = $this->request->getPost('payment_method') ?? 'free';
        // A course is only free if the admin explicitly marked it as free with the is_free flag.
        $isFree = ($course->is_free == 1);

        // Auto-approve free courses
        if ($isFree || $paymentMethod === 'free') {
            $enrollmentId = $this->courseEnrollmentsModel->createEnrollment($userId, $course->id, [
                'paid_amount' => 0,
                'payment_method' => 'free',
                'auto_approve' => true
            ]);

            if ($enrollmentId) {
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
            'paid_amount' => $course->course_price,
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

        $adminEmail = setting('App.contact_email');
        if (empty($adminEmail)) {
            $emailConfig = config('Email');
            $adminEmail = $emailConfig->fromEmail ?: 'webeasystep@gmail.com';
        }

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
