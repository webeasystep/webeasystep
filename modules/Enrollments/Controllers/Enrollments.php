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

        session()->remove('selected_course');
        return redirect()->to('/enrollments/my-courses')
            ->with('success', 'تم إرسال طلب الشراء بنجاح. سيتم مراجعته من قبل الإدارة وتفعيل اشتراكك بعد التحقق من الدفع.');
    }
}
