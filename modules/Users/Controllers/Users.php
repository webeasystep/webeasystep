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
            $credentials = [
                'full_name' => $this->request->getPost('full_name'),
                'email'    => $mobile.'@msarlink.com',
                'mobile' => $this->request->getPost('mobile'),
                'password' => $this->request->getPost('password'),
                'active' => 1
            ];

            file_put_contents('d:/laragon/www/msarlink/registration_test.log', date('Y-m-d H:i:s') . " - Creating User entity with data: " . json_encode($credentials) . "\n", FILE_APPEND);
            $user = new User($credentials);
            file_put_contents('d:/laragon/www/msarlink/registration_test.log', date('Y-m-d H:i:s') . " - User entity created, toArray: " . json_encode($user->toArray()) . "\n", FILE_APPEND);
            
            // Save the user first to get the ID
            if ($users->save($user)) {
                $userId = $users->getInsertID();
                file_put_contents('d:/laragon/www/msarlink/registration_test.log', date('Y-m-d H:i:s') . " - User saved successfully, ID: " . $userId . "\n", FILE_APPEND);
                
                // Reload the user with the ID to ensure it's complete
                $user = $users->find($userId);
                file_put_contents('d:/laragon/www/msarlink/registration_test.log', date('Y-m-d H:i:s') . " - User reloaded with ID: " . $user->id . "\n", FILE_APPEND);
                
                // Now login with the complete user object
                auth()->login($user);
                file_put_contents('d:/laragon/www/msarlink/registration_test.log', date('Y-m-d H:i:s') . " - User logged in successfully\n", FILE_APPEND);
                
                // إعادة توجيه إلى صفحة الكورسات
                return redirect()->to('/courses/my_courses')->with('success', 'تم التسجيل بنجاح!');
            } else {
                file_put_contents('d:/laragon/www/msarlink/registration_test.log', date('Y-m-d H:i:s') . " - Failed to save user: " . json_encode($users->errors()) . "\n", FILE_APPEND);
            }

        }

        $data['title'] = lang('Site.register');

        return MainView('site_layout/shield/register', $data);
    }

    /**
     * Show login form
     */
    public function login()
    {
        // Check if user is already logged in using CodeIgniter Shield
        if (auth()->loggedIn() || (session()->get('user')['id'] ?? null)) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login',
            'validation' => $this->validator
        ];
        return MainView('site_layout/shield/login', $data);
    }

    /**
     * Process login using Shield authentication
     */
    public function processLogin()
    {
        if (!$this->validate($this->loginRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Find user by mobile number
        $mobile = $this->request->getPost('mobile');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('mobile', $mobile)->first();

        if (!$user || !password_verify($password, $user->password_hash)) {
            return redirect()->back()->withInput()->with('error', 'رقم الهاتف أو كلمة المرور غير صحيحة.');
        }

        // Check if user is active
        if (!$user->active) {
            return redirect()->back()->withInput()->with('error', 'حسابك غير مفعل. يرجى التواصل مع الإدارة.');
        }

        // Set session data
        session()->set([
            'user_id' => $user->id,
            'user_mobile' => $user->mobile,
            'user_name' => $user->full_name,
            'logged_in' => true
        ]);

        $remember = (bool) $this->request->getPost('remember');

        return redirect()->to('/dashboard')->with('success', 'مرحباً بك مرة أخرى!');
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
