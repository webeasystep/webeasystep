<?php

namespace Modules\Enrollments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\FireUploader;
use Modules\Courses\Models\CoursesModel;
use Modules\Enrollments\Models\EnrollmentsModel;
use Modules\Users\Models\UsersModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Enrollments extends BaseController
{
    protected EnrollmentsModel $enrollmentsModel;
    protected CoursesModel     $coursesModel;
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
        $this->coursesModel     = new CoursesModel();
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
     * Renders the checkout page to handle:
     *   1) Free course
     *   2) Waiting list
     *   3) Paid course
     */
    public function checkout(int $courseId)
    {
        $course = $this->coursesModel->find($courseId);
        if (!$course) {
            $this->show_msg('danger', 'Error', 'Course not found.');
            return redirect()->back();
        }

        $isFree        = ($course->is_free == 1);
        $isWaitingList = (!empty($course->waiting_list) && $course->waiting_list == 1);
        $isLoggedIn    = auth()->loggedIn();

        $data = [
            'title'         => 'Checkout / Enroll in Course',
            'course'        => $course,
            'isFree'        => $isFree,
            'isWaitingList' => $isWaitingList,
            'isLoggedIn'    => $isLoggedIn,
        ];

        // If POST, handle the enrollment logic
        if ($this->request->is('post')) {
            return $this->completeEnrollment($course);
        }

        return view('site/checkout', $data);
    }

    /**
     * Main entry point for completing an enrollment:
     *   - Free course
     *   - Waiting list
     *   - Paid course (user logged in / not logged in)
     */
    private function completeEnrollment(object $course)
    {
        if (!$course) {
            $this->show_msg('danger', 'Error', 'Invalid course.');
            return redirect()->back();
        }

        $isFree        = ($course->is_free == 1);
        $isWaitingList = (!empty($course->waiting_list) && $course->waiting_list == 1);
        $isLoggedIn    = auth()->loggedIn();
        $userId        = $isLoggedIn ? auth()->user()->id : null;

        if ($isFree) {
            return $this->handleFreeCourse($course, $userId);
        }

        if ($isWaitingList) {
            return $this->handleWaitingList($course, $userId);
        }

        // If paid course but user not logged in => create user
        if (!$isLoggedIn) {
            return $this->handlePaidNotLoggedIn($course);
        }

        // If paid course and user is logged in => requires proof of payment
        return $this->handlePaidLoggedIn($course, $userId);
    }

    /**
     * Scenario: Free course => enroll user immediately, redirect to course_view.
     */
    private function handleFreeCourse(object $course, ?int $userId)
    {
        if (!$userId) {
            // If you want to require login for free courses, handle it here
            return redirect()->to('/site/login')->with('error', 'Please log in first.');
        }

        // Immediately enroll user
        $this->enrollmentsModel->enrollUser($userId, $course->id);
        $this->show_msg('success', 'Enrolled', 'You have been enrolled in this free course!');

        // Redirect to course_view
        return redirect()->to('/courses/course_view/' . $course->slug);
    }

    /**
     * Scenario: Waiting list => show message or store user in a waiting list table if needed.
     */
    private function handleWaitingList(object $course, ?int $userId)
    {
        $this->show_msg('success', 'Waiting List', 'You have been added to the waiting list. We will contact you later.');
        return redirect()->back();
    }

    /**
     * Scenario: Paid course but user not logged in => create new user, then proceed as if logged in.
     */
    private function handlePaidNotLoggedIn(object $course)
    {
        if (!$this->validate($this->rules)) {
            $errors = implode('<br>', $this->validator->getErrors());
            $this->show_msg('danger', 'Validation Errors', $errors);
            return redirect()->back()->withInput();
        }

        // Create new user
        $userData = [
            'full_name'  => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'country'    => $this->request->getPost('country'),
            'mobile'     => $this->request->getPost('phone'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $newUserId = $this->usersModel->insert($userData);

        if (!$newUserId) {
            $this->show_msg('danger', 'Error', 'Failed to create user account.');
            return redirect()->back();
        }

        // Optionally log them in automatically
        auth()->loginById($newUserId);

        // Now treat them as a logged-in user for paid enrollment
        return $this->handlePaidLoggedIn($course, $newUserId);
    }

    /**
     * Scenario: Paid course + user is logged in => requires proof of payment, then enrollment is pending.
     */
    private function handlePaidLoggedIn(object $course, int $userId)
    {

        // Payment proof is required

        if (empty($_FILES['proof_image']['name'])) {
            $this->show_msg('danger', 'Error', 'Payment proof is required.');
            return redirect()->back()->withInput();
        }

        // Insert pending enrollment record
        $enrollmentData = [
            'user_id'          => $userId,
            'course_id'        => $course->id,
            'amount'           => $course->price,
            'enrollment_method'=> 'instapay',
            'status'           => 'pending',
            'enrolled_at'      => date('Y-m-d H:i:s'),
        ];
        $enrollmentId = $this->enrollmentsModel->insert($enrollmentData);

        if (!$enrollmentId) {
            $this->show_msg('danger', 'Error', 'Failed to save payment data.');
            return redirect()->back();
        }

        // Upload proof_image
        $this->fireUploader->upload_photos($this->enrollmentsModel, 'proof_image', $enrollmentId);

        // We do NOT enrollUser(...) yet because admin must approve
        $this->show_msg('success', 'Payment Received', 'Your payment proof was uploaded. Enrollment is pending admin review.');

        return redirect()->to('/courses/my_courses');
    }
}
