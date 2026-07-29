<?php

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use App\Libraries\UserType;
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
        'mobile' => [
            'label' => 'الجوال',
            'rules' => 'required|regex_match[/^(05|5|96605|9665|\+9665|\+96605)[0-9]{8}$/]',
            'errors' => [
                'regex_match' => 'يرجى إدخال رقم جوال سعودي صحيح يبدأ بـ 05 (مثال: 0512345678)'
            ]
        ]
    ];

    private $loginRules = [
        'mobile' => [
            'label' => 'الجوال',
            'rules' => 'required|regex_match[/^(05|5|96605|9665|\+9665|\+96605)[0-9]{8}$/]',
            'errors' => [
                'regex_match' => 'يرجى إدخال رقم جوال سعودي صحيح يبدأ بـ 05 (مثال: 0512345678)'
            ]
        ],
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
     * Attempts to register a student account.
     */
    public function register(): string|RedirectResponse
    {
        return $this->handleRegistration(UserType::STUDENT, array_merge([
            'title' => lang('Site.register'),
            'register_heading' => 'إنشاء حساب طالب',
            'register_description' => 'أنشئ حسابك كطالب وابدأ الوصول إلى مقرراتك ومتابعة رحلتك التعليمية.',
            'register_badge' => 'تسجيل الطلاب',
            'submit_label' => 'تسجيل كطالب',
            'terms_url' => site_url('terms-conditions'),
            'terms_label' => 'الشروط والأحكام الخاصة بالطلاب',
        ], $this->getRegistrationMobileViewData(UserType::STUDENT)));
    }

    /**
     * Display and process instructor registration using the same form fields.
     */
    public function instructorRegister(): string|RedirectResponse
    {
        return $this->handleRegistration(UserType::INSTRUCTOR, array_merge([
            'title' => 'تسجيل المحاضرين',
            'register_heading' => 'إنشاء حساب محاضر',
            'register_description' => 'سجّل كمحاضر لإدارة مقرراتك، متابعة طلباتك، والوصول إلى لوحة التحكم الخاصة بك.',
            'register_badge' => 'تسجيل المحاضرين',
            'submit_label' => 'تسجيل كمحاضر',
            'terms_url' => site_url('instructor-terms'),
            'terms_label' => 'دليل وحقوق الشراكة للمحاضر',
        ], $this->getRegistrationMobileViewData(UserType::INSTRUCTOR)));
    }

    /**
     * Shared registration flow for students and instructors.
     */
    private function handleRegistration(int $userType, array $viewData): string|RedirectResponse
    {
        if (auth()->loggedIn()) {
            return redirect()->to($this->getUserRedirectUrl(auth()->user()));
        }

        if (!setting('Auth.allowRegistration')) {
            $this->show_msg('danger', lang('Auth.registrationDisabled'), lang('Auth.registerNotAllowed'));
            return redirect()->back()->withInput();
        }

        if ($this->request->is('post')) {
            log_message('debug', 'Registration POST request received');

            $mobileValidation = $this->getMobileValidationConfig($userType);

            // Get validation rules
            $rules = [
                'full_name' => [
                    'label' => 'الاسم الكامل',
                    'rules' => 'required|min_length[3]|max_length[50]'
                ],
                'email' => [
                    'label' => 'البريد الإلكتروني',
                    'rules' => 'required|valid_email|is_unique[auth_identities.secret]'
                ],
                'mobile' => [
                    'label' => $mobileValidation['label'],
                    'rules' => $mobileValidation['rules'],
                    'errors' => [
                        'regex_match' => $mobileValidation['message'],
                    ],
                ],
                'password' => [
                    'label' => 'كلمة المرور',
                    'rules' => 'required|min_length[6]'
                ],
                'password_confirm' => [
                    'label' => 'تأكيد كلمة المرور',
                    'rules' => 'required|matches[password]'
                ],
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
            
            // Normalize the mobile number according to the selected registration flow.
            $fullMobile = $this->normalizeMobileNumber((string) $this->request->getPost('mobile'), $userType);
            
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            // Check if mobile is already used
            $existingMobile = $this->db->table('users')
                ->where('mobile', $fullMobile)
                ->get()->getRow();
            if ($existingMobile) {
                log_message('debug', 'Registration failed: Mobile number ' . $fullMobile . ' already exists.');
                $this->show_msg('danger', 'خطأ في التسجيل', 'رقم الجوال مسجل بالفعل');
                return redirect()->back()->withInput();
            }
            
            // Create user credentials
            // Shield's EmailActivator will handle the activation email automatically if configured in Auth.php
            $credentials = [
                'full_name' => $this->request->getPost('full_name'),
                'email' => $email,
                'mobile' => $fullMobile,
                'user_type' => UserType::normalize($userType),
                'password' => $password,
            ];

            $user = new User($credentials);
            
            // Save the user first to get the ID
            if ($users->save($user)) {
                $userId = $users->getInsertID();
                $user = $users->find($userId);
                
                log_message('debug', 'Registration - User saved with ID: ' . $userId);
                
                try {
                    session()->set('register_user_type', UserType::normalize($userType));

                    // Trigger Shield's register action (Email Activator)
                    $authenticator = auth('session')->getAuthenticator();

                    // Clear any pending or stale session data to prevent LogicException
                    auth()->logout();
                    
                    // Set the user in session so they are "pending"
                    // This is required for the ActionController to access the user
                    $authenticator->startLogin($user);
                    
                    try {
                        // Set up the activation action in session
                        // This creates the initial identity and sets 'auth_action' session key
                        if ($authenticator->startUpAction('register', $user)) {
                            // Redirect to the action controller which will send the email and show the view
                            return redirect()->to('auth/a/show');
                        }
                    } catch (\Throwable $e) {
                        log_message('error', 'Registration - Action startup warning: ' . $e->getMessage());
                        // If user is created and logged in as pending, redirect to activation page
                        return redirect()->to('auth/a/show');
                    }
                    
                    // Fallback if no action is defined
                    return redirect()->to($this->getUserRedirectUrl($user));
                    
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

        $data = array_merge([
            'title' => lang('Site.register'),
            'register_heading' => 'إنشاء حساب',
            'register_description' => 'أنشئ حسابك وابدأ استخدام المنصة.',
            'register_badge' => 'تسجيل',
            'alternate_register_label' => null,
            'alternate_register_url' => null,
            'submit_label' => lang('Auth.register'),
            'terms_url' => site_url('terms-conditions'),
            'terms_label' => 'الشروط والأحكام',
        ], $viewData);

        return MainView('site_layout/shield/register', $data);
    }

    /**
     * Returns the validation rules for the selected registration mobile format.
     */
    private function getMobileValidationConfig(int $userType): array
    {
        if (UserType::normalize($userType) === UserType::INSTRUCTOR) {
            return [
                'label' => 'رقم الموبايل',
                'rules' => 'required|regex_match[/^(01[0125][0-9]{8}|1[0125][0-9]{8}|201[0125][0-9]{8}|\+201[0125][0-9]{8})$/]',
                'message' => 'يرجى إدخال رقم محمول مصري صحيح يبدأ بـ 010 أو 011 أو 012 أو 015 (مثال: 01012345678).',
            ];
        }

        return [
            'label' => 'الجوال',
            'rules' => 'required|regex_match[/^(05|5|96605|9665|\+9665|\+96605)[0-9]{8}$/]',
            'message' => 'يرجى إدخال رقم جوال سعودي صحيح يبدأ بـ 05 (مثال: 0512345678).',
        ];
    }

    /**
     * Normalizes the incoming mobile number to the locally stored format.
     */
    private function normalizeMobileNumber(string $rawMobile, int $userType): string
    {
        $digitsOnly = preg_replace('/[^\d]/', '', trim($rawMobile));

        if (UserType::normalize($userType) === UserType::INSTRUCTOR) {
            if (str_starts_with($digitsOnly, '20')) {
                $digitsOnly = substr($digitsOnly, 2);
            }

            if (str_starts_with($digitsOnly, '1') && strlen($digitsOnly) === 10) {
                $digitsOnly = '0' . $digitsOnly;
            }

            return $digitsOnly;
        }

        if (str_starts_with($digitsOnly, '966')) {
            $digitsOnly = substr($digitsOnly, 3);
        }

        if (str_starts_with($digitsOnly, '5') && strlen($digitsOnly) === 9) {
            $digitsOnly = '0' . $digitsOnly;
        }

        return $digitsOnly;
    }

    /**
     * Returns the mobile field UI data for the selected registration flow.
     */
    private function getRegistrationMobileViewData(int $userType): array
    {
        if (UserType::normalize($userType) === UserType::INSTRUCTOR) {
            return [
                'mobile_country_flag' => '🇪🇬',
                'mobile_country_code' => '+20',
                'mobile_placeholder' => '01012345678',
                'mobile_pattern' => '^(01[0125][0-9]{8}|1[0125][0-9]{8}|201[0125][0-9]{8}|\\+201[0125][0-9]{8})$',
                'mobile_title' => 'يرجى إدخال رقم محمول مصري صحيح يبدأ بـ 010 أو 011 أو 012 أو 015',
                'mobile_help_text' => 'مثال: 01012345678',
                'mobile_maxlength' => 13,
            ];
        }

        return [
            'mobile_country_flag' => '🇸🇦',
            'mobile_country_code' => '+966',
            'mobile_placeholder' => '0512345678',
            'mobile_pattern' => '^(05|5|96605|9665|\\+9665|\\+96605)[0-9]{8}$',
            'mobile_title' => 'يرجى إدخال رقم جوال سعودي يبدأ بـ 05 ويتكون من 10 أرقام',
            'mobile_help_text' => 'مثال: 0512345678',
            'mobile_maxlength' => 14,
        ];
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
            log_message('debug', 'Users::login - User already logged in, redirecting to user destination');
            return redirect()->to($this->getUserRedirectUrl(auth()->user()));
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

        // Clear any pending or stale session data to prevent LogicException
        auth()->logout();

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

        // Register/update device session for student
        if ($loggedInUser && UserType::isStudent($loggedInUser) && !$loggedInUser->inGroup('superadmin') && !$loggedInUser->inGroup('admin')) {
            /** @var \Modules\Users\Models\UserDeviceModel $deviceModel */
            $deviceModel = model(\Modules\Users\Models\UserDeviceModel::class);
            $agent = $this->request->getUserAgent();
            $deviceKey = md5((string)$agent);
            $deviceName = ($agent->getBrowser() ?: 'Browser') . ' on ' . ($agent->getPlatform() ?: 'Device');
            $deviceModel->registerOrUpdateDevice(
                $loggedInUser->id,
                $deviceKey,
                $deviceName,
                (string)$agent,
                $this->request->getIPAddress(),
                session_id()
            );
        }

        // Shield handles session management automatically
        // Redirect to intended URL or default dashboard
        $redirectURL = $this->getUserRedirectUrl($loggedInUser, session('redirect_url'));
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

        $user = auth()->user();
        if ($user) {
            if (UserType::isInstructor($user)) {
                return redirect()->to($this->getUserRedirectUrl($user));
            }

            // REGISTER DEVICE & ENFORCE SINGLE ACTIVE SESSION:
            $deviceKey = $_COOKIE['fk_device_key'] ?? $this->request->getPost('device_key') ?? ('dev_' . md5($this->request->getUserAgent()->getAgentString() . $this->request->getIPAddress()));
            $userAgent = $this->request->getUserAgent()->getAgentString();
            $ipAddress = $this->request->getIPAddress();
            $sessionId = session_id();

            $agent = $this->request->getUserAgent();
            $deviceName = ($agent->getPlatform() ?: 'Unknown OS') . ' / ' . ($agent->getBrowser() ?: 'Browser') . ' ' . ($agent->getVersion() ?: '');

            /** @var \Modules\Users\Models\UserDeviceModel $deviceModel */
            $deviceModel = model(\Modules\Users\Models\UserDeviceModel::class);
            $deviceResult = $deviceModel->registerOrUpdateDevice($user->id, $deviceKey, $deviceName, $userAgent, $ipAddress, $sessionId);

            if (!$deviceResult['status']) {
                auth()->logout();
                $this->show_msg('danger', 'تنبيه الأجهزة المصرح بها', $deviceResult['message']);
                return redirect()->to('/login');
            }

            if (!empty($deviceResult['is_suspicious'])) {
                log_message('warning', 'Suspicious device activity for user ID: ' . $user->id . ' Total unique devices: ' . $deviceResult['total_devices']);
            }
        }

        // Check for intended course first
        $intendedCourse = session()->get('intended_course');
        if ($intendedCourse) {
            session()->remove('intended_course');
            log_message('debug', 'POST_LOGIN_REDIRECT: Redirecting to intended course: ' . $intendedCourse);
            return redirect()->to('courses/course_view/' . $intendedCourse);
        }

        // Default redirect to user destination
        $redirectURL = $this->getUserRedirectUrl($user);
        log_message('debug', 'POST_LOGIN_REDIRECT: Redirecting to default destination: ' . $redirectURL);
        return redirect()->to($redirectURL);
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

    /**
     * Resolves the correct post-authentication destination for the given user.
     */
    private function getUserRedirectUrl(?User $user, ?string $studentFallback = null): string
    {
        if (UserType::isInstructor($user)) {
            return site_url(UserType::getDefaultPath(UserType::INSTRUCTOR));
        }

        if (! empty($studentFallback)) {
            return $studentFallback;
        }

        return site_url(UserType::getDefaultPath(UserType::STUDENT));
    }

}
