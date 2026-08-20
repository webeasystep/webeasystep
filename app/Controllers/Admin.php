<?php

namespace App\Controllers;

use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use CodeIgniter\Session\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\LoginModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Authentication\Authenticators;
use DateTime;
use DateTimeZone;
use Modules\Areas\Models\AreasModel;
use Modules\Pages\Models\PagesModel;

class Admin extends BaseController
{
    protected $auth;

    /**
     * @var Session
     */

    protected $pages;
    protected $areas;
    protected $config;


    public function __construct()
    {
        $this->config = config('Auth');
        $this->auth = service('Auth');
        $this->pages = new PagesModel();
    }

    public function dashboard(): string
    {
        $data['title'] = lang('Admin.dashboard');
        $data['menu'] = 'dashboard';
        $data['sub-menu'] = 'dashboard';

        // Setting the time zone
        $this->db->query("SET time_zone='+3:00'");

        // 1. المدفوعات (الاشتراكات المكتملة/المفعلة - كعدد فقط)
        $data['paid_count'] = (int) ($this->db->query("
            SELECT COUNT(DISTINCT CASE 
                WHEN bundle_id IS NOT NULL AND batch_id IS NOT NULL THEN CONCAT('b_', batch_id, '_', bundle_id)
                ELSE CONCAT('s_', id)
            END) as count 
            FROM tb_course_enrollments 
            WHERE status = 'approved'
        ")->getRow()?->count ?? 0);

        // 2. مدفوعات معلقة (عدد فقط)
        $data['pending_count'] = (int) ($this->db->query("
            SELECT COUNT(DISTINCT CASE 
                WHEN bundle_id IS NOT NULL AND batch_id IS NOT NULL THEN CONCAT('b_', batch_id, '_', bundle_id)
                ELSE CONCAT('s_', id)
            END) as count 
            FROM tb_course_enrollments 
            WHERE status = 'pending'
        ")->getRow()?->count ?? 0);

        // 3. الطلبة (user_type = 1)
        $data['students_count'] = (int) ($this->db->query("
            SELECT COUNT(*) as count FROM users WHERE user_type = 1
        ")->getRow()?->count ?? 0);

        // 4. المحاضرين (user_type = 2)
        $data['instructors_count'] = (int) ($this->db->query("
            SELECT COUNT(*) as count FROM users WHERE user_type = 2
        ")->getRow()?->count ?? 0);

        // 5. طلبات المقررات
        $data['course_requests_count'] = (int) ($this->db->query("
            SELECT COUNT(*) as count FROM tb_course_requests
        ")->getRow()?->count ?? 0);

        return MainView('admin_layout/dashboard', $data);
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
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Get the credentials for login
            $remember = (bool)$this->request->getPost('remember');
            $credentials = [
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password')
            ];
            $loginAttempt = auth()->remember($remember)->attempt($credentials);

            if (!$loginAttempt->isOK()) {
                return redirect()->back()->with('error', $loginAttempt->reason());
            }

            $_SESSION['user_id'] = auth()->user()->id;
            $_SESSION['user_name'] = auth()->user()->username;

            session()->removeTempdata('beforeLoginUrl');
            unset($_SESSION['beforeLoginUrl'], $_SESSION['redirect_url']);

            return redirect()->to(site_url('/dt_admin/dashboard'))->withCookies()->with('message', lang('Auth.loginSuccess'));
        }

        // If it's a GET request, display login form or redirect if logged in as admin
        if (auth()->loggedIn()) {
            if (auth()->user()->inGroup('superadmin') || auth()->user()->inGroup('admin')) {
                return redirect()->to(site_url('/dt_admin/dashboard'));
            }
            // If logged in as normal user on frontend, log out to allow admin login
            auth()->logout();
        }
        // Set a return URL if none is specified
        $data['title'] = lang('Auth.login');
        return MainView($this->config->adminViews['login'], $data);
    }

    /**
     * Log the user out.
     */
    public function logout(): RedirectResponse
    {
        if (auth()->loggedIn()) {
            auth()->logout();
        }

        return redirect()->to(site_url('/dt_admin/login'));
    }

    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Displays the user registration page.
     */
    public function register()
    {
        // If it's a POST request, we'll handle the registration attempt
        if ($this->request->is('post')) {
            // Check if registration is allowed
            if (!setting('Auth.allowRegistration')) {
                return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
            }
            // Get the User Provider (UserModel by default)
            $users = auth()->getProvider();

            // Validate basics first since some password rules rely on these fields
            $rules = config('Validation')->registrationRules ?? [
                    'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
                    'email' => 'required|valid_email|is_unique[auth_identities.secret]',
                    'password' => 'required',
                    'password_confirm' => 'required|matches[password]',
                ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Save the user with optional config fields
            $allowedPostFields = array_merge(['password'], ['username'], $this->config->validFields, $this->config->personalFields);

            $user = new User($this->request->getPost($allowedPostFields));

            if (!$users->save($user)) {
                return redirect()->back()->withInput()->with('errors', $users->errors());
            }

            // To get the complete user object with ID, we need to get from the database
            $user = $users->findById($users->getInsertID());

            // Add to default group
            $users->addToDefaultGroup($user);

            // Success!
            return redirect()->route('dt_admin/login')->with('message', lang('Auth.registerSuccess'));
        }

        // If it's a GET request, we'll display the registration form or redirect if already logged in
        if (auth()->loggedIn()) {
            return redirect()->back();
        }

        // Check if registration is allowed
        if (!setting('Auth.allowRegistration')) {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }
        $data['title'] = lang('Auth.register');
        return MainView($this->config->adminViews['register'], $data);
    }

    //--------------------------------------------------------------------
    // Forgot Password
    //--------------------------------------------------------------------

    /**
     * Attempts to find a user account with that password
     * and send password reset instructions to them.
     */
    public function forget_password()
    {
        // If it's a POST request, we'll handle the forgot password attempt
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
            $email = trim($this->request->getPost('email'));
            $user = model(UserModel::class)->findByCredentials(['email' => $email]);

            if (!$user) {
                // Return success to prevent email enumeration
                return redirect()->route('dt_admin/reset_password')->with('message', 'إذا كان البريد الإلكتروني مسجلاً لدينا، فستصلك رسالة تحتوي على رمز إعادة التعيين.');
            }

            $identityModel = model(UserIdentityModel::class);

            // Delete any previous password_reset identities
            $identityModel->deleteIdentitiesByType($user, 'password_reset');

            // Generate the code and save it as an identity
            helper('text');
            $token = random_string('numeric', 6);

            $identityModel->insert([
                'user_id' => $user->id,
                'type' => 'password_reset',
                'secret' => $token,
                'name' => 'password_reset',
                'expires' => Time::now()->addSeconds(config('Auth')->passwordResetCodeLifetime ?? 3600)->format('Y-m-d H:i:s'),
            ]);

            // Send Email
            $email = \Config\Services::email();
            $email->setFrom(env('MAIL_FROM_EMAIL', 'support@fakhrcs.com'), env('MAIL_FROM_NAME', 'FakhrCS'));
            $email->setTo($user->email);
            $email->setSubject(lang('Auth.forgotPassword'));
            $email->setMessage(MainView($this->config->adminViews['password_reset_email'], ['code' => $token]));
            
            if (!$email->send()) {
                log_message('error', $email->printDebugger(['headers']));
                return redirect()->back()->with('error', lang('Auth.unableSendEmailToUser', [$user->email]));
            }
            $email->clear();

            return redirect()->route('dt_admin/reset_password')->with('message', 'إذا كان البريد الإلكتروني مسجلاً لدينا، فستصلك رسالة تحتوي على رمز إعادة التعيين.');
        }

        // If it's a GET request, we'll display the forgot password form
        $data['title'] = lang('Auth.forgotPassword');
        return MainView($this->config->adminViews['forget_password'], $data);
    }



    /**
     * @param int|string|null $userId
     */
    private function recordLoginAttempt(
        string $identifier,
        bool $success,
        $userId = null
    ): void
    {
        /** @var LoginModel $loginModel */
        $loginModel = model(LoginModel::class);

        $loginModel->recordLoginAttempt(
            \CodeIgniter\Shield\Authentication\Authenticators\Session::ID_TYPE_MAGIC_LINK,
            $identifier,
            $success,
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent(),
            $userId
        );
    }


    public function reset_password()
    {
        // If it's a POST request, we'll handle the password reset attempt
        if ($this->request->is('post')) {
            $rules = [
                'email' => 'required|valid_email',
                'token' => 'required|numeric|exact_length[6]',
                'password' => 'required|strong_password',
                'password_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $email = trim($this->request->getPost('email'));
            $token = trim($this->request->getPost('token'));

            $users = model(UserModel::class);
            $user = $users->findByCredentials(['email' => $email]);

            if (null === $user) {
                return redirect()->back()->withInput()->with('error', lang('Auth.invalidActivateToken'));
            }

            $identityModel = model(UserIdentityModel::class);
            $identity = $identityModel->getIdentityBySecret('password_reset', $token);

            // Verify token exists and belongs to the user
            if ($identity === null || (int)$identity->user_id !== (int)$user->id) {
                return redirect()->back()->withInput()->with('error', lang('Auth.invalidActivateToken'));
            }

            // Verify token is not expired
            if (Time::now()->isAfter($identity->expires)) {
                return redirect()->back()->withInput()->with('error', lang('Auth.magicLinkExpired'));
            }

            // Success! Save the new password, and cleanup the reset hash.
            $user->password = $this->request->getPost('password');
            if (property_exists($user, 'force_pass_reset')) {
                $user->force_pass_reset = false;
            }
            $users->save($user);

            $identityModel->delete($identity->id);

            return redirect()->route('dt_admin/login')->with('message', lang('Auth.resetSuccess'));
        }

        // If it's a GET request, we'll display the reset password form
        $data['title'] = lang('Auth.resetPassword');
        return MainView($this->config->adminViews['reset_password'], $data);
    }


    /**
     * Activate account.
     *
     * @return mixed
     */
    public function activateAccount()
    {
        $users = model(PagesModel::class);

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

        if (null === $user) {
            return redirect()->route('dt_admin/login')->with('error', lang('Auth.activationNoUser'));
        }

        $user->activate();

        $users->save($user);

        return redirect()->route('dt_admin/login')->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Resend activation account.
     *
     * @return mixed
     */
    public function resendActivateAccount()
    {
        if (!setting('Auth.requireActivation')) {
            return redirect()->route('dt_admin/login');
        }

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $login = urldecode($this->request->getGet('login'));
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $users = model(PagesModel::class);

        $user = $users->where($type, $login)
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()->route('dt_admin/login')->with('error', lang('Auth.activationNoUser'));
        }

        $activator = service('activator');
        if (ENVIRONMENT != "production") {
            return redirect()->back()->withInput()->with('errors', lang('Auth.unknownError'));
        }
        $sent = $activator->send($user);

        if (!$sent) {
            return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
        }

        // Success!
        return redirect()->route('dt_admin/login')->with('message', lang('Auth.activationSuccess'));
    }

}
