<?php

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Hermawan\DataTables\DataTable;
use Modules\Users\Models\UsersModel;
use Modules\Pages\Models\PagesModel;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Events\Events;

class Users extends BaseController
{
    protected $userModel;

    private $rules = [
        'full_name' => ['rules' => 'required|min_length[2]|max_length[100]'],
        'mobile' => ['rules' => 'required|exact_length[11]|regex_match[/^(010|011|012|015)[0-9]{8}$/]']
    ];

    private $loginRules = [
        'mobile' => ['rules' => 'required|exact_length[11]|regex_match[/^(010|011|012|015)[0-9]{8}$/]'],
        'password' => ['rules' => 'required']
    ];

    public function __construct()
    {
        $this->userModel = new UsersModel();
        helper(['form', 'function', 'url']);
    }

    public function index()
    {
        $data = [
            'title' => 'User list',
            'roles' => $this->userModel->getRole()
        ];
        echo view('user/index', $data);
    }
    /**
     * Attempts to register the user.
     * Now requires both email and mobile with country code selection.
     * User must verify email before they can login.
     */
    public function register(): string|RedirectResponse
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

            // Get validation rules from config
            $rules = config('Validation')->registrationRules ?? [
                'full_name' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email|is_unique[auth_identities.secret]',
                // 'country_code' => 'required|valid_country_code', // Removed
                'mobile' => 'required', // Simplified validation
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
            ];

            log_message('debug', 'Validation rules: ' . json_encode($rules));
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

            if (!$this->validate($rules)) {
                log_message('debug', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                $this->show_msg('danger', lang('Auth.validationErrors'), $this->validator->getErrors(), 1000000);
                return redirect()->back()->withInput();
            }

            // Get Shield's user provider
            $users = auth()->getProvider();
            
            // Normalize mobile number with country code - REMOVED
            // $countryCode = $this->request->getPost('country_code');
            $mobileNumber = $this->request->getPost('mobile');
            // $fullMobile = normalize_mobile($mobileNumber, $countryCode);
            $fullMobile = $mobileNumber; // Use mobile as is
            
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            // Check if mobile is already used
            // Query auth_identities instead of users table
            $existingMobile = $this->db->table('auth_identities')
                ->where('type', 'mobile_password')
                ->where('secret', $fullMobile)
                ->get()->getRow();
            if ($existingMobile) {
                log_message('debug', 'Registration failed: Mobile number ' . $fullMobile . ' already exists.');
                $this->show_msg('danger', 'خطأ في التسجيل', 'رقم الهاتف مسجل بالفعل');
                return redirect()->back()->withInput();
            }
            
            // Create user credentials
            // Shield's EmailActivator will handle the activation email automatically if configured in Auth.php
            $credentials = [
                'full_name' => $this->request->getPost('full_name'),
                // 'active' is handled by Shield based on requireActivation config
            ];

            $user = new User($credentials);
            
            // Save the user first to get the ID
            if ($users->save($user)) {
                $userId = $users->getInsertID();
                $user = $users->find($userId);
                
                log_message('debug', 'Registration - User saved with ID: ' . $userId);
                
                /** @var \Modules\Users\Models\UserIdentityModel $identityModel */
                $identityModel = model(\Modules\Users\Models\UserIdentityModel::class);
                
                try {
                    // Create email identity with password
                    $identityModel->createEmailIdentity($user, [
                        'email' => $email,
                        'password' => $password
                    ]);
                    
                    log_message('debug', 'Registration - Email identity created successfully for user: ' . $userId);
                    
                    // Also create mobile identity for mobile login option
                    $identityModel->createMobileIdentity($user, [
                        'mobile' => $fullMobile,
                        'password' => $password
                    ]);
                    
                    log_message('debug', 'Registration - Mobile identity created successfully for user: ' . $userId);

                    // Trigger Shield's register action (Email Activator)
                    $authenticator = auth('session')->getAuthenticator();
                    
                    // Set the user in session so they are "pending"
                    // This is required for the ActionController to access the user
                    $authenticator->startLogin($user);
                    
                    // Set up the activation action in session
                    // This creates the initial identity and sets 'auth_action' session key
                    if ($authenticator->startUpAction('register', $user)) {
                        // Redirect to the action controller which will send the email and show the view
                        return redirect()->route('auth-action-show');
                    }
                    
                    // Fallback if no action is defined (though it should be)
                    // Redirect based on Auth config or default
                    return redirect()->to(config('Auth')->redirects['register'] ?? '/');
                    
                } catch (\Exception $e) {
                    log_message('error', 'Registration - Failed to create identity: ' . $e->getMessage());
                    $this->show_msg('danger', 'خطأ في التسجيل', 'حدث خطأ أثناء إنشاء الحساب');
                    return redirect()->back()->withInput();
                }
                
            } else {
                log_message('error', 'Registration - Failed to save user: ' . json_encode($users->errors()));
                $this->show_msg('danger', 'خطأ في التسجيل', 'فشل في حفظ بيانات المستخدم');
                return redirect()->back()->withInput();
            }

        }

        $data['title'] = lang('Site.register');

        return MainView('site_layout/shield/register', $data);
    }

    // sendWelcomeEmail and buildWelcomeEmailHtml removed in favor of Shield's EmailActivator

    public function testEmail()
    {
        return view('Modules\Users\Views\Site\emails\activation', [
            'full_name' => 'تجربة المستخدم',
            'activation_url' => base_url('users/verify-email/test-token-123')
        ]);
    }

    /**
     * Display login form or redirect if already logged in
     */
    public function login()
    {
        log_message('debug', 'Users::login - Method: ' . $this->request->getMethod());
        log_message('debug', 'Users::login - Already logged in: ' . (auth()->loggedIn() ? 'true' : 'false'));

        // If user is already logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            log_message('debug', 'Users::login - User already logged in, redirecting to enrollments/my-courses');
            return redirect()->to('/enrollments/my-courses');
        }

        // If it's a POST request, process the login
        if ($this->request->is('post')) {
            log_message('debug', 'Users::login - Processing POST request');
            return $this->processLogin();
        }

        // Display login form
        $data['title'] = 'تسجيل الدخول';
        log_message('debug', 'Users::login - Displaying login form');
        return MainView('site_layout/shield/login', $data);
    }

    /**
     * Process login using CodeIgniter Shield
     */
    public function processLogin()
    {
        log_message('debug', 'Users::processLogin - Method: ' . $this->request->getMethod());
        log_message('debug', 'Users::processLogin - POST data: ' . json_encode($this->request->getPost()));

        // Validation rules for email and password
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('debug', 'Users::processLogin - Validation failed: ' . json_encode($errors));
            session()->setFlashdata('errors', $errors);
            return redirect()->back()->withInput();
        }

        log_message('debug', 'Users::processLogin - Validation passed');

        // Get the credentials for login
        $remember = (bool)$this->request->getPost('remember');
        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        log_message('debug', 'Users::processLogin - Attempting login with email: ' . $credentials['email']);
        log_message('debug', 'Users::processLogin - Remember me: ' . ($remember ? 'true' : 'false'));

        // Use Shield's attempt method for authentication
        $loginAttempt = auth()->remember($remember)->attempt($credentials);

        log_message('debug', 'Users::processLogin - Login attempt completed');
        log_message('debug', 'Users::processLogin - Login result isOK: ' . ($loginAttempt->isOK() ? 'true' : 'false'));

        if (!$loginAttempt->isOK()) {
            log_message('debug', 'Users::processLogin - Login failed: ' . $loginAttempt->reason());
            log_message('debug', 'Users::processLogin - Extra info: ' . json_encode($loginAttempt->extraInfo()));

            // Set error message in Arabic
            $errorMessage = 'غير قادر على تسجيل دخولك. يرجى التحقق من بيانات الاعتماد الخاصة بك.';
            session()->setFlashdata('error', $errorMessage);
            return redirect()->back()->withInput();
        }

        $loggedInUser = auth()->user();
        log_message(
            'debug',
            'Users::processLogin - Login successful for user ID: ' . ($loggedInUser->id ?? 'unknown')
        );
        log_message('debug', 'Users::processLogin - User logged in check: ' . (auth()->loggedIn() ? 'true' : 'false'));

        // Shield handles session management automatically
        // Redirect to intended URL or default dashboard
        $redirectURL = session('redirect_url') ?? site_url('/enrollments/my-courses');
        session()->remove('redirect_url');

        log_message('debug', 'Users::processLogin - Redirecting to: ' . $redirectURL);

        // Set success message
        session()->setFlashdata('success', 'تم تسجيل الدخول بنجاح');

        return redirect()->to($redirectURL)->withCookies();
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
        log_message('debug', 'POST_LOGIN_REDIRECT: Redirecting to enrollments/my-courses');
        return redirect()->to('enrollments/my-courses');
    }

    /**
     * Handle email activation link from verification email.
     * Redirects to the Shield activation verify form with the token.
     */
    // activateAccount removed

    /**
     * Logout user using Shield authentication
     */
    public function logout()
    {
        auth()->logout();
        return redirect()->to(site_url('/'));
    }

    public function ForgotPassword()
    {
        $config = config('Auth');
        if ($config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        if ($this->request->is('post')) {
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

        return MainView($config->views['forgot'], ['config' => $config]);
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
     * Verify email with token
     */
    // Custom activation methods removed (verifyEmail, verifyEmailSent, sendVerificationEmail)
    // Shield handles these via its RegisterController and EmailActivator

}
