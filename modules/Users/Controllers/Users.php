<?php

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use Hermawan\DataTables\DataTable;
use Modules\Users\Models\UsersModel;

class Users extends BaseController
{
    protected $userModel;

    private $registrationRules = [
        'full_name' => ['rules' => 'required|min_length[2]|max_length[100]'],
        'email' => ['rules' => 'required|valid_email|is_unique[users.email]'],
        'mobile' => ['rules' => 'required|min_length[10]|max_length[15]'],
        'password' => ['rules' => 'required|min_length[8]'],
        'password_confirm' => ['rules' => 'required|matches[password]'],
        'birth_date' => ['rules' => 'required|valid_date'],
        'parent_name' => ['rules' => 'permit_empty|min_length[2]|max_length[100]'],
        'parent_email' => ['rules' => 'permit_empty|valid_email'],
        'parent_phone' => ['rules' => 'permit_empty|min_length[10]|max_length[20]']
    ];

    private $loginRules = [
        'email' => ['rules' => 'required|valid_email'],
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
     * Show registration form
     */
    public function register()
    {
        $data = [
            'title' => 'Register',
            'validation' => $this->validator
        ];
        return View('Site', 'register', $data);
    }

    /**
     * Process registration
     */
    public function processRegistration()
    {
        if (!$this->validate($this->registrationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Check if user is under 18 and requires parent info
        $birthDate = new \DateTime($data['birth_date']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;
        
        if ($age < 18) {
            if (empty($data['parent_name']) || empty($data['parent_email'])) {
                return redirect()->back()->withInput()->with('error', 'Parent information is required for users under 18.');
            }
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password_confirm']);
        
        // Set default values
        $data['active'] = 0; // Inactive until email verification
        $data['credits'] = 0.00;
        $data['group_id'] = 2; // Default user group
        
        try {
            $userId = $this->userModel->insert($data);
            
            if ($userId) {
                // Generate verification token and send email
                $token = $this->userModel->generateVerificationToken($userId);
                $this->sendVerificationEmail($data['email'], $token);
                
                return redirect()->to('/users/verify-email-sent')->with('success', 'Registration successful! Please check your email to verify your account.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
        
        return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
    }

    /**
     * Show login form
     */
    public function login()
    {
        if (session()->get('user_id')) {
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

        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];
        
        $remember = (bool) $this->request->getPost('remember');
        
        /** @var Session $authenticator */
        $authenticator = auth('session');
        
        $result = $authenticator->remember($remember)->attempt($credentials);
        
        if (! $result->isOK()) {
            return redirect()->back()->withInput()->with('error', $result->reason());
        }
        
        // Get the authenticated user
        $user = $authenticator->getUser();
        
        // Update last active
        $this->userModel->update($user->id, ['last_active' => date('Y-m-d H:i:s')]);
        
        return redirect()->to('/dashboard')->with('success', 'Welcome back!');
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
