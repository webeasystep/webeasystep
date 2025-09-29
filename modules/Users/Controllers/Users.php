<?php

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Hermawan\DataTables\DataTable;
use Modules\Users\Models\UsersModel;

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

            $rules = config('Validation')->registrationRules ?? [
                'full_name' => 'required|min_length[3]|max_length[50]',
                'mobile' => 'required|egyptian_mobile',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
            ];


            log_message('debug', 'Validation rules: ' . json_encode($rules));
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

            if (!$this->validate($rules)) {
                log_message('debug', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                $this->show_msg('danger', lang('Auth.validationErrors'), $this->validator->getErrors(),1000000);
                return redirect()->back()->withInput();
            }

            // Get Shield's user provider
            $users = auth()->getProvider();
            $mobile = $this->request->getPost('mobile');
            $password = $this->request->getPost('password');
            
            $credentials = [
                'full_name' => $this->request->getPost('full_name'),
                'email'    => $mobile.'@msarlink.com',
                'mobile' => $this->request->getPost('mobile'),
                'active' => 1
            ];

            $user = new User($credentials);
            // Save the user first to get the ID
            if ($users->save($user)) {
                $userId = $users->getInsertID();
                // Reload the user with the ID to ensure it's complete
                $user = $users->find($userId);
                
                log_message('debug', 'Registration - User saved with ID: ' . $userId);
                
                // Create mobile_password identity in auth_identities table
                /** @var \Modules\Users\Models\UserIdentityModel $identityModel */
                $identityModel = model(\Modules\Users\Models\UserIdentityModel::class);
                
                try {
                    // Create mobile identity with password
                    $identityModel->createMobileIdentity($user, [
                        'mobile' => $mobile,
                        'password' => $password
                    ]);
                    
                    log_message('debug', 'Registration - Mobile identity created successfully for user: ' . $userId);
                    
                    // Now login with the complete user object
                    auth()->login($user);
                    // إعادة توجيه إلى صفحة الكورسات
                    $this->show_msg('success','تم بنجاح','يمكنك استعراض الوحدات المتاحة وشراءها');
                    return redirect()->to('/courses');
                    
                } catch (\Exception $e) {
                    log_message('error', 'Registration - Failed to create mobile identity: ' . $e->getMessage());
                    $this->show_msg('danger', 'خطأ في التسجيل', 'حدث خطأ أثناء إنشاء الهوية المحمولة');
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

    /**
     * Display login form or redirect if already logged in
     */
    public function login()
    {
        log_message('debug', 'Users::login - Method: ' . $this->request->getMethod());
        log_message('debug', 'Users::login - Already logged in: ' . (auth()->loggedIn() ? 'true' : 'false'));

        // If user is already logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            log_message('debug', 'Users::login - User already logged in, redirecting to courses/my_courses');
            return redirect()->to('/courses/my_courses');
        }

        // If it's a POST request, process the login
        if (strtoupper($this->request->getMethod()) === 'POST') {
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

        // Validation rules for mobile and password
        $rules = [
            'mobile' => 'required|egyptian_mobile',
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
            'mobile'    => $this->request->getPost('mobile'),
            'password' => $this->request->getPost('password')
        ];

        log_message('debug', 'Users::processLogin - Attempting login with credentials: ' . json_encode(['mobile' => $credentials['mobile']]));
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

        log_message('debug', 'Users::processLogin - Login successful for user ID: ' . auth()->user()->id);
        log_message('debug', 'Users::processLogin - User logged in check: ' . (auth()->loggedIn() ? 'true' : 'false'));

        // Shield handles session management automatically
        // Redirect to intended URL or default dashboard
        $redirectURL = session('redirect_url') ?? site_url('/courses/my_courses');
        session()->remove('redirect_url');

        log_message('debug', 'Users::processLogin - Redirecting to: ' . $redirectURL);

        // Set success message
        session()->setFlashdata('success', 'تم تسجيل الدخول بنجاح');

        return redirect()->to($redirectURL)->withCookies();
    }

    /**
     * Logout user using Shield authentication
     */
    public function logout()
    {
        auth()->logout();
        return redirect()->to('/users/login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Verify email with token
     */
    public function verifyEmail($token = null)
    {
        if (!$token) {
            return redirect()->to('/users/login')->with('error', 'Invalid verification token.');
        }

        $user = $this->userModel->verifyEmail($token);

        if ($user) {
            return redirect()->to('/users/login')->with('success', 'Email verified successfully! You can now login.');
        }

        return redirect()->to('/users/login')->with('error', 'Invalid or expired verification token.');
    }

    /**
     * Show email verification sent page
     */
    public function verifyEmailSent()
    {
        $data = ['title' => 'Email Verification Sent'];
        return View('Site', 'verify_email_sent', $data);
    }

    /**
     * Send verification email
     */
    private function sendVerificationEmail($email, $token)
    {
        $verificationUrl = base_url("users/verify-email/{$token}");

        // Log email attempt
        $this->db->table('tb_email_logs')->insert([
            'recipient_email' => $email,
            'email_type' => 'verification',
            'subject' => 'Verify Your Email Address',
            'template_used' => 'email_verification',
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Here you would integrate with your email service
        // For now, we'll just log it
        log_message('info', "Verification email sent to {$email} with URL: {$verificationUrl}");
    }

}
