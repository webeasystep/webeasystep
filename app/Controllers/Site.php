<?php

namespace App\Controllers;

use App\Models\BaseModel;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Exceptions\ValidationException;
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
        'full_name' => ['rules' => 'required|alpha_numeric_space|is_unique[users.full_name,id,{id}]'],
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

        // 2) Get courses data from Courses controller
        $coursesController = new \Modules\Courses\Controllers\Courses();
        $data['courses'] = $coursesController->getCoursesForHome();

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

    /**
     * Handle post-login redirect to intended course or my_courses
     */
    public function handlePostLoginRedirect()
    {
        // Log the redirect handling
        log_message('debug', 'POST_LOGIN_REDIRECT: Handling post-login redirect');

        // Verify user is still logged in
        if (!auth()->loggedIn()) {
            log_message('error', 'POST_LOGIN_REDIRECT: User not logged in during redirect handling');
            return redirect()->to('/login')->with('error', 'Session expired. Please log in again.');
        }

        log_message('debug', 'POST_LOGIN_REDIRECT: User authenticated, proceeding with redirect');

        // Check for intended course first
        $intendedCourse = session()->get('intended_course');
        if ($intendedCourse) {
            session()->remove('intended_course');
            log_message('debug', 'POST_LOGIN_REDIRECT: Redirecting to intended course: ' . $intendedCourse);
            return redirect()->to('courses/course_view/' . $intendedCourse);
        }

        // Default redirect to my_courses
        log_message('debug', 'POST_LOGIN_REDIRECT: Redirecting to my_courses');
        return redirect()->to('courses/my_courses');
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    public function login()
    {
        // If it's a POST request, we'll handle the login attempt
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required',
            ];

            if (!$this->validate($rules)) {
                session()->setFlashdata('errors', $this->validator->getErrors());
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
                session()->setFlashdata('errors', [$loginAttempt->reason()]);
                return redirect()->back()->withInput();
            }

            $_SESSION['user_id'] = auth()->user()->id;
            $_SESSION['full_name'] = auth()->user()->full_name;

            $redirectURL = session('redirect_url') ?? site_url('/login');
            unset($_SESSION['redirect_url']);
            $this->show_msg('success', lang('Auth.loginSuccess'), "مرحبًا بك مرة أخرى");
            return redirect()->to($redirectURL)->withCookies();
        }

        // If it's a GET request, we'll display the login form or redirect if already logged in
        if (auth()->loggedIn()) {
            $redirectURL =  site_url('/courses/my_courses');
            return redirect()->to($redirectURL);
        }
        // Set a return URL if none is specified
        $data['title'] = lang('Auth.login');
        return MainView('site_layout/shield/login', $data);
    }

    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Attempts to register the user.
     */
    public function register()
    {
        if (auth()->loggedIn()) {
            return redirect()->back();
        }

        if (!setting('Auth.allowRegistration')) {
            $this->show_msg('danger', lang('Auth.registrationDisabled'), lang('Auth.registerNotAllowed'));
            return redirect()->back()->withInput();
        }

        if ($this->request->is('post')) {
            log_message('debug', 'Registration POST request received');

            $rules = config('Validation')->registrationRules ?? [
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[auth_identities.secret]',
                'mobile' => 'required|egyptian_mobile',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
            ];

            log_message('debug', 'Validation rules: ' . json_encode($rules));
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

            if (!$this->validate($rules)) {
                log_message('debug', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                $this->show_msg('danger', lang('Auth.validationErrors'), $this->validator->getErrors());
                return redirect()->back()->withInput();
            }

            log_message('debug', 'Validation passed, proceeding with user creation');

            // Get Shield's user provider
            $users = auth()->getProvider();

            // Create User entity with basic user data (no email/password - those go in auth_identities)
            $userData = [
                'full_name' => $this->request->getPost('full_name'),
                'mobile' => $this->request->getPost('mobile'),
                'active' => 0, // Set as inactive - will be activated via email
            ];

            log_message('debug', 'Creating User entity with data: ' . json_encode($userData));
            $user = new User($userData);
            log_message('debug', 'User entity created, toArray: ' . json_encode($user->toArray()));

            // Save the user first to get the ID
            if (!$users->save($user)) {
                log_message('debug', 'User save failed: ' . json_encode($users->errors()));
                $this->show_msg('danger', lang('Auth.registrationFailed'), $users->errors());
                return redirect()->back()->withInput();
            }

            log_message('debug', 'User saved successfully, ID: ' . $users->getInsertID());

            // Get the complete user object with ID
            $user = $users->findById($users->getInsertID());

            // Create email/password identity in auth_identities table
            $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);
            $credentials = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password')
            ];

            log_message('debug', 'Creating email identity for user ID: ' . $user->id . ' with email: ' . $credentials['email']);

            try {
                $identityModel->createEmailIdentity($user, $credentials);
                log_message('debug', 'Email identity created successfully in auth_identities table');
            } catch (\Exception $e) {
                log_message('error', 'Failed to create email identity: ' . $e->getMessage());
                // Clean up - delete the user record since identity creation failed
                $users->delete($user->id);
                $this->show_msg('danger', 'Registration failed', 'Could not create user credentials');
                return redirect()->back()->withInput();
            }

            // Add to default group
            $users->addToDefaultGroup($user);

            if (setting('Auth.requireActivation')) {
                log_message('debug', 'Activation required - sending activation email');

                // Send activation email using EmailActivator
                $emailActivator = new \App\Libraries\EmailActivator();
                $emailSent = $emailActivator->send($user);

                if (!$emailSent) {
                    log_message('error', 'Failed to send activation email: ' . $emailActivator->error());
                    // Clean up the user record if email fails
                    $users->delete($user->id);
                    $identityModel->where('user_id', $user->id)->delete();
                    return redirect()->back()->withInput()->with('error', 'فشل في إرسال رسالة التفعيل. يرجى المحاولة مرة أخرى.');
                }

                // Show success message and redirect to activation info page
                $this->show_msg('success', 'تم إنشاء الحساب بنجاح', 'تم إرسال رسالة التفعيل إلى بريدك الإلكتروني. يرجى التحقق من صندوق الوارد وتفعيل حسابك.');
                return redirect()->to('/activation-sent');
            }



            // Redirect to post-login handler instead of direct redirect
            return redirect()->to('courses/my_courses');
        }

        $data['title'] = lang('Site.register');

        return MainView('site_layout/shield/register', $data);
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
        if ($this->request->is('post')) {
            $users = model(UserModel::class);

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
        return MainView('site_layout/shield/reset', ['token' => $token]);
    }

    /**
     * Activate account.
     *
     * @return mixed
     */
    public function activateAccount()
    {
        $users = model(UserModel::class);
        $authModel = model(\App\Models\AuthModel::class);
        $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);

        // First things first - log the activation attempt.
        $authModel->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent()
        );

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $token = $this->request->getGet('token');

        // Find the identity with the activation token
        $identity = $identityModel->where('type', 'email_activate')
            ->where('secret', $token)
            ->first();

        if ($identity === null) {
            $this->show_msg('danger', 'خطأ في التفعيل', 'رمز التفعيل غير صحيح أو منتهي الصلاحية');
            return redirect()->route('login');
        }

        // Get the user associated with this identity
        $user = $users->find($identity->user_id);

        if ($user === null || $user->active == 1) {
            $this->show_msg('danger', 'خطأ في التفعيل', 'المستخدم غير موجود أو مفعل بالفعل');
            return redirect()->route('login');
        }

        // Activate the user
        $user->active = 1;
        $users->save($user);

        // Ensure the user has an email_password identity for login
        $emailPasswordIdentity = $identityModel->where('user_id', $user->id)
            ->where('type', 'email_password')
            ->first();
        
        if (!$emailPasswordIdentity) {
            log_message('error', 'No email_password identity found for user ID: ' . $user->id . ' during activation');
            $this->show_msg('danger', 'خطأ في التفعيل', 'حدث خطأ أثناء تفعيل الحساب. يرجى المحاولة مرة أخرى.');
            return redirect()->route('login');
        }

        // Delete only the activation identity, preserve the email_password identity
        $identityModel->where('id', $identity->id)->delete();

        // Auto-login the user after successful activation
        log_message('debug', 'Auto-login after activation for user ID: ' . $user->id);
        
        // Login the user using Shield's auth system
        auth()->login($user);
        
        // Force session write to ensure login state persists
        session()->markAsFlashdata('temp_key');
        session()->remove('temp_key');
        
        // Verify login was successful immediately
        if (!auth()->loggedIn()) {
            log_message('error', 'Auto-login failed after activation for user ID: ' . $user->id);
            $this->show_msg('success', 'تم التفعيل بنجاح', 'تم تفعيل حسابك بنجاح، يمكنك الآن تسجيل الدخول');
            return redirect()->route('login');
        }

        log_message('debug', 'Auto-login verified successful for user ID: ' . $user->id);

        // Set success message in session before redirect
        session()->setFlashdata('message', 'مرحباً بك!');
        session()->setFlashdata('message_type', 'success');
        session()->setFlashdata('message_details', 'تم تفعيل حسابك وتسجيل دخولك بنجاح');
        
        // Check for intended course first
        $intendedCourse = session()->get('intended_course');
        if ($intendedCourse) {
            session()->remove('intended_course');
            log_message('debug', 'POST_LOGIN_REDIRECT: Redirecting to intended course: ' . $intendedCourse);
            return redirect()->to('courses/view/' . $intendedCourse);
        }

        // Default redirect to my courses
        log_message('debug', 'POST_LOGIN_REDIRECT: Redirecting to courses/my_courses');
        return redirect()->to('courses/my_courses');
    }

    /**
     * Send welcome email after successful activation
     *
     * @param object $user
     * @return void
     */
    private function sendWelcomeEmail($user)
    {
        try {
            $email = service('email');

            // Configure email
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setTo($user->email);
            $email->setSubject('مرحباً بك في MSARLink - تم تفعيل حسابك بنجاح');

            // Load welcome email template
            $welcomeTemplate = view('site_layout/shield/Email/welcome_email', [
                'user' => $user,
                'siteName' => 'MSARLink'
            ]);

            $email->setMessage($welcomeTemplate);

            // Send email
            $sent = $email->send();

            if ($sent) {
                log_message('info', 'Welcome email sent successfully to: ' . $user->email);
            } else {
                log_message('error', 'Failed to send welcome email to: ' . $user->email . ' - ' . $email->printDebugger());
            }

        } catch (\Exception $e) {
            log_message('error', 'Exception sending welcome email: ' . $e->getMessage());
        }
    }

}
