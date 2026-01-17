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
                'country_code' => 'required|valid_country_code',
                'mobile' => 'required|valid_mobile',
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
            
            // Normalize mobile number with country code
            $countryCode = $this->request->getPost('country_code');
            $mobileNumber = $this->request->getPost('mobile');
            $fullMobile = normalize_mobile($mobileNumber, $countryCode);
            
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            // Check if mobile is already used
            $existingMobile = $this->db->table('users')->where('mobile', $fullMobile)->get()->getRow();
            if ($existingMobile) {
                $this->show_msg('danger', 'خطأ في التسجيل', 'رقم الهاتف مسجل بالفعل');
                return redirect()->back()->withInput();
            }
            
            // Create user credentials - user is inactive until email is verified
            $credentials = [
                'full_name' => $this->request->getPost('full_name'),
                'email'    => $email,
                'mobile' => $fullMobile,
                'active' => 0  // User is inactive until email verified
            ];

            $user = new User($credentials);
            
            // Save the user first to get the ID
            if ($users->save($user)) {
                $userId = $users->getInsertID();
                $user = $users->find($userId);
                
                log_message('debug', 'Registration - User saved with ID: ' . $userId);
                
                // Create email_password identity in auth_identities table
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
                    
                    // Generate email verification token
                    $token = $this->userModel->generateVerificationToken($userId);
                    
                    // Send welcome email with activation link
                    $this->sendWelcomeEmail($email, $this->request->getPost('full_name'), $token);
                    
                    // Redirect to verification sent page
                    $this->show_msg('success', 'تم التسجيل بنجاح', 'تم إرسال رابط تفعيل الحساب إلى بريدك الإلكتروني. يرجى التحقق من البريد الوارد.');
                    return redirect()->to('/users/verify-email-sent');
                    
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

    /**
     * Send welcome email with activation link
     */
    private function sendWelcomeEmail(string $email, string $fullName, string $token): bool
    {
        $activationUrl = base_url("users/verify-email/{$token}");
        
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom(setting('Email.fromEmail') ?? 'noreply@msarlink.com', setting('Email.fromName') ?? 'مسار لينك');
            $emailService->setTo($email);
            $emailService->setSubject('مرحباً بك في مسار لينك - تفعيل الحساب');
            
            // Build welcome email HTML
            $message = $this->buildWelcomeEmailHtml($fullName, $activationUrl);
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                log_message('info', "Welcome email sent to {$email}");
                
                // Log email
                $this->db->table('tb_email_logs')->insert([
                    'recipient_email' => $email,
                    'email_type' => 'welcome_activation',
                    'subject' => 'مرحباً بك في مسار لينك - تفعيل الحساب',
                    'template_used' => 'welcome_email',
                    'status' => 'sent',
                    'sent_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                return true;
            } else {
                log_message('error', "Failed to send welcome email to {$email}: " . $emailService->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', "Exception sending welcome email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Build welcome email HTML content
     */
    private function buildWelcomeEmailHtml(string $fullName, string $activationUrl): string
    {
        return <<<HTML
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في مسار لينك</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; direction: rtl; text-align: right; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 28px;">🎉 مرحباً بك في مسار لينك!</h1>
        </div>
        <div style="padding: 30px;">
            <p style="font-size: 18px; color: #333; margin-bottom: 20px;">عزيزي/عزيزتي <strong>{$fullName}</strong>،</p>
            <p style="font-size: 16px; color: #555; line-height: 1.8;">شكراً لتسجيلك في منصة <strong>مسار لينك</strong> التعليمية. نحن سعداء بانضمامك إلينا!</p>
            <p style="font-size: 16px; color: #555; line-height: 1.8;">لتفعيل حسابك والبدء في رحلتك التعليمية، يرجى الضغط على الزر أدناه:</p>
            <div style="text-align: center; margin: 30px 0;">
                <a href="{$activationUrl}" 
                   style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; 
                          padding: 15px 40px; text-decoration: none; border-radius: 50px; font-size: 18px; font-weight: bold;
                          box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                    تفعيل الحساب ✓
                </a>
            </div>
            <p style="font-size: 14px; color: #888; line-height: 1.6;">أو يمكنك نسخ الرابط التالي في متصفحك:</p>
            <p style="font-size: 12px; color: #667eea; word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 5px;">{$activationUrl}</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
            <p style="font-size: 12px; color: #999; text-align: center;">
                إذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذه الرسالة.<br>
                © مسار لينك - جميع الحقوق محفوظة
            </p>
        </div>
    </div>
</body>
</html>
HTML;
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
