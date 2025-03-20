<?php

namespace App\Controllers;

use App\Models\BaseModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use Modules\Pages\Controllers\Pages;
use Modules\Pages\Models\PagesModel;

class Site extends BaseController
{
    protected $auth;
    private BaseModel $baseModel;
    protected $config;
    private $rules = [
        //     'name' => ['rules' => 'required'],
        'username' => ['rules' => 'required|alpha_numeric|is_unique[tb_users.username,id,{id}]'],
        // 'email' => ['rules' => 'required|valid_email|is_unique[tb_users.email,id,{id}]'],
        //'mobile' => ['rules' => 'required'],
        //  'role' => ['rules' => 'required']
    ];

    public function __construct()
    {
        $this->config = config('Auth');
        $this->baseModel = new BaseModel();
    }

    public function index()
    {
        $this->home();
    }

    public function home()
    {
        $data['page_name'] = 'home';
        $data['title']     = lang('Site.home');

        // 1) Articles
        $data['articles'] = $this->db
            ->table('articles')
            ->where('active', 1)
            ->get()
            ->getResultArray();

        // 2) Categories
        $data['categories'] = $this->db
            ->table('tb_categories')
            ->where('active', 1)
            ->get()
            ->getResultArray();

        // 3) Courses
        $data['courses'] = $this->db
            ->table('tb_courses')
            ->where('active', 1)
            ->get()
            ->getResultArray();

        // (Optional) Check if user is logged in
        $userId = session()->get('user_id');
        $enrolledCourseIds = [];

        // If user is logged in, fetch the courses they’re enrolled in
        if (!empty($userId)) {
            $userCourses = $this->db
                ->table('tb_enrollments')  // <-- use the correct table name here
                ->select('course_id')
                ->where('user_id', $userId)
                ->get()
                ->getResultArray();

            // Extract course_ids into a simple array
            $enrolledCourseIds = array_column($userCourses, 'course_id');
        }

        // Pre-process each course
        foreach ($data['courses'] as &$course) {
            // Provide a fallback if short_desc doesn't exist
            $course['short_desc'] = $course['short_desc'] ?? '';

            // Count lessons from JSON structure
            $lessonCount = 0;
            if (!empty($course['course_structure'])) {
                $structure = json_decode($course['course_structure'], true);
                if (is_array($structure)) {
                    foreach ($structure as $section) {
                        if (!empty($section['videos']) && is_array($section['videos'])) {
                            $lessonCount += count($section['videos']);
                        }
                    }
                }
            }
            $course['lesson_count'] = $lessonCount;

            // Mark if user is enrolled
            $course['is_enrolled'] = in_array($course['id'], $enrolledCourseIds);
        }
        unset($course); // Good practice after reference loops

        // 4) Possibly fetch “about us” page or other custom pages
        $data['about_us'] = $this->db
            ->table('pages')
            ->where('active', '1')
            ->where('page_link', 'about_us')
            ->get()
            ->getRowArray();

        // Provide a unified data array if needed
        $data['data'] = $data;

        // 5) Render the main home view
        echo MainView('site_layout/home', $data);
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    public function login()
    {
        // If it's a POST request, we'll handle the login attempt
        if ($this->request->is('post')) {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required',
            ];

            if (!$this->validate($rules)) {
                $this->show_msg('danger', "خطا في بيانات الدخول", $this->validator->getErrors());
                return redirect()->back()->withInput();
            }

            // Get the credentials for login
            $remember = (bool)$this->request->getPost('remember');
            $credentials = [
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password')
            ];
            $loginAttempt = auth()->remember($remember)->attempt($credentials);

            if (!$loginAttempt->isOK()) {
                $this->show_msg('danger', "خطا في بيانات الدخول", $loginAttempt->reason());
                return redirect()->back()->withInput();
            }

            $_SESSION['user_id'] = auth()->user()->id;
            $_SESSION['user_name'] = auth()->user()->username;

            $redirectURL = session('redirect_url') ?? site_url('/login');
            unset($_SESSION['redirect_url']);
            $this->show_msg('success', lang('Auth.loginSuccess'), "مرحبًا بك مرة أخرى");
            return redirect()->to($redirectURL)->withCookies();
        }

        // If it's a GET request, we'll display the login form or redirect if already logged in
        if (auth()->loggedIn()) {
            $redirectURL =  site_url('/exams');
            return redirect()->to($redirectURL);
        }
        // Set a return URL if none is specified
        $data['title'] = lang('Auth.login');
        return MainView($this->config->siteViews['login'], $data);
    }

    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    public function register()
    {
        if (auth()->loggedIn()) {
            return redirect()->back();
        }

        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $postData = $this->request->getPost($allowedPostFields);

        if (!setting('Auth.allowRegistration')) {
            $this->show_msg('danger', lang('Auth.registrationDisabled'), lang('Auth.registerNotAllowed'));
            return redirect()->back()->withInput();
        }

        if ($this->request->is('post')) {
            $rules = config('Validation')->registrationRules ?? [
                'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]',
                'email' => 'required|valid_email|is_unique[auth_identities.secret]',
                'password' => 'required|strong_password',
                'password_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                $this->show_msg('danger', lang('Auth.validationErrors'), $this->validator->getErrors());
                return redirect()->back()->withInput();
            }

            $users = model(UserModel::class);
            $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
            $user = new User($this->request->getPost($allowedPostFields));

            if (!empty($this->config->defaultUserGroup)) {
                $users = $users->withGroup($this->config->defaultUserGroup);
            }

            if (!$users->save($user)) {
                $this->show_msg('danger', lang('Auth.registrationFailed'), $users->errors());
                return redirect()->back()->withInput();
            }

            $user = $users->findById($users->getInsertID());
            $users->addToDefaultGroup($user);

            if (setting('Auth.requireActivation')) {
                $activator = service('activator');
                if (ENVIRONMENT !== 'production') {
                    $sent = $activator->send($user);
                    if (!$sent) {
                        $this->show_msg('danger', lang('Auth.activationFailed'), $activator->error() ?? lang('Auth.unknownError'));
                        return redirect()->back()->withInput();
                    }
                }
                $this->show_msg('success', lang('Auth.activationSuccess'), lang('Auth.checkEmailForActivation'));
                return redirect()->route('login');
            }

            // Auto-login after successful registration
            auth()->login($user);
            $this->show_msg('success', lang('Auth.registerSuccess'), "تم تسجيل الدخول بنجاح!");
            return redirect()->to('/exams/start');
        }

        $data['page_name'] = 'register';
        $data['title'] = lang('Site.register');
        return MainView($this->config->siteViews['register'], $data);
    }

    //--------------------------------------------------------------------
    //  logout
    //--------------------------------------------------------------------
    /**
     * Log the user out.
     */

    public function logout(): RedirectResponse
    {
        auth()->logout();
        return redirect()->to(site_url('/'));
    }
    public function ForgotPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'email' => [
                    'label' => lang('Auth.emailAddress'),
                    'rules' => 'required|valid_email',
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $users = model(PagesModel::class);
            $user = $users->where('email', $this->request->getPost('email'))->first();

            if (null === $user) {
                return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
            }

            // Save the reset hash
            $user->generateResetHash();
            $users->save($user);

            $resetter = service('resetter');
            if (ENVIRONMENT !== "production") {
                return redirect()->back()->withInput()->with('errors', lang('Auth.unknownError'));
            }

            $sent = $resetter->send($user);

            if (!$sent) {
                return redirect()->back()->withInput()->with('error', $resetter->error() ?? lang('Auth.unknownError'));
            }

            return redirect()->route('reset-password')->with('message', lang('Auth.forgotEmailSent'));
        }

        return MainView($this->config->views['forgot'], ['config' => $this->config]);
    }

    public function ResetPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        if ($this->request->getMethod() === 'post') {
            $users = model(PagesModel::class);

            // Log the reset attempt
            $users->logResetAttempt(
                $this->request->getPost('email'),
                $this->request->getPost('token'),
                $this->request->getIPAddress(),
                (string)$this->request->getUserAgent()
            );

            $rules = [
                'token' => 'required',
                'email' => 'required|valid_email',
                'password' => 'required|strong_password',
                'password_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $user = $users->where('email', $this->request->getPost('email'))
                ->where('reset_hash', $this->request->getPost('token'))
                ->first();

            if (null === $user) {
                return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
            }

            // Reset token still valid?
            if (!empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
                return redirect()->back()->withInput()->with('error', lang('Auth.resetTokenExpired'));
            }

            // Save the new password and cleanup the reset hash
            $user->password = $this->request->getPost('password');
            $user->reset_hash = null;
            $user->reset_at = date('Y-m-d H:i:s');
            $user->reset_expires = null;
            $user->force_pass_reset = false;
            $users->save($user);

            return redirect()->route('login')->with('message', lang('Auth.resetSuccess'));
        }

        $token = $this->request->getGet('token');
        return MainView($this->config->views['reset'], ['config' => $this->config, 'token' => $token]);
    }
    /**
     * Activate account.
     *
     * @return mixed
     */
    public function activateAccount()
    {
        $users = model(Pages::class);

        // First things first - log the activation attempt.
        $users->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent()
        );

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $user = $users->where('activate_hash', $this->request->getGet('token'))
            ->where('active', 0)
            ->first();


    }



}
