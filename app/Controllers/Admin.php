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

        //  in_groups('admin')

        $data['section_name'] = "dashboard";
        $query = $this->db->query("SELECT  COUNT(*) as all_orders  FROM  contact_us  ");

        $result = $query->getRow();

        $data['all_orders'] = $result->all_orders ?? 0;
        $data['pending_orders'] = $result->pending_orders ?? 0;
        $data['accepted_orders'] = $result->accepted_orders ?? 0;
        $data['taken_orders'] = $result->taken_orders ?? 0;
        $data['canceled_orders'] = $result->canceled_orders ?? 0;
        $data['finished_orders'] = $result->finished_orders ?? 0;
        $data['total_orders_today'] = $result->total_add_orders ?? 0;
        $data['attended_drivers'] = 11;
        return MainView('admin_layout/dashboard', $data);
    }
    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    public function login()
    {
        //  echo password_hash('123456', PASSWORD_DEFAULT);exit;
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

            $redirectURL = session('redirect_url') ?? site_url('/dt_admin');
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirectURL)->withCookies()->with('message', lang('Auth.loginSuccess'));
        }

        // If it's a GET request, we'll display the login form or redirect if already logged in
        if (auth()->loggedIn()) {
            $redirectURL =  site_url('/dt_admin/dashboard');
            return redirect()->to($redirectURL);
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
            if (!setting('Auth.allowMagicLinkLogins')) {
                return redirect()->route('dt_admin/login')->with('error', lang('Auth.forgotDisabled'));
            }

            $rules = [
                'email' => [
                    'label' => lang('Auth.emailAddress'),
                    'rules' => 'required|valid_email',
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $user = model(UserModel::class)->findByCredentials(['email' => $this->request->getPost('email')]);

            if (!$user) {
                return redirect()->back()->with('error', lang('Auth.invalidEmail'));
            }

            $identityModel = model(UserIdentityModel::class);

            // Delete any previous forget_password identities
            $identityModel->deleteIdentitiesByType($user, Authenticators\Session::ID_TYPE_MAGIC_LINK);

            // Generate the code and save it as an identity
            helper('text');
            $token = random_string('crypto', 20);

            $identityModel->insert([
                'user_id' => $user->id,
                'type' => Authenticators\Session::ID_TYPE_MAGIC_LINK,
                'secret' => $token,
                'expires' => Time::now()->addSeconds(setting('Auth.magicLinkLifetime'))->format('Y-m-d H:i:s'),
            ]);

            $this->send_mail($user, $token);

            return redirect()->route('dt_admin/reset_password')->with('message', lang('Auth.forgotEmailSent'));
        }

        // If it's a GET request, we'll display the forgot password form
        if (!setting('Auth.allowMagicLinkLogins')) {
            return redirect()->route('dt_admin/login')->with('error', lang('Auth.forgotDisabled'));
        }
        $data['title'] = lang('Auth.useMagicLink');
        return MainView($this->config->adminViews['forget_password'], $data);
    }

    public function send_mail($user, $token): RedirectResponse
    {
        $email = \Config\Services::email();
        $email->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? "");
        $email->setTo($user->email);
        $email->setSubject(lang('Auth.magicLinkSubject'));
        $email->setMessage(MainView($this->config->adminViews['magic-link-email'], ['token' => $token]));
        // Clear the email
        if ($email->send()) {
            echo 'Email successfully sent';
            $email->clear();
        } else {
            log_message('error', $email->printDebugger(['headers']));
            return redirect()->route('dt_admin/magic_link')->with('error', lang('Auth.unableSendEmailToUser', [$user->email]));
        }

    }


    /**
     * Handles the GET request from the email
     */
    public function verify_magic_link(): RedirectResponse
    {
        $token = $this->request->getGet('token');

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        $identity = $identityModel->getIdentityBySecret(Authenticators\Session::ID_TYPE_MAGIC_LINK, $token);

        $identifier = $token ?? '';

        // No token found?
        if ($identity === null) {
            $this->recordLoginAttempt($identifier, false);

            $credentials = ['magicLinkToken' => $token];
            Events::trigger('failedLogin', $credentials);

            return redirect()->route('dt_admin/forget_password')->with('error', lang('Auth.magicTokenNotFound'));
        }

        // Delete the db entry so it cannot be used again.
        $identityModel->delete($identity->id);

        // Token expired?
        if (Time::now()->isAfter($identity->expires)) {
            $this->recordLoginAttempt($identifier, false);

            $credentials = ['magicLinkToken' => $token];
            Events::trigger('failedLogin', $credentials);

            return redirect()->route('forget_password')->with('error', lang('Auth.magicLinkExpired'));
        }

        // Get our login redirect url
        return redirect()->to('dt_admin/reset_password');
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


    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @return mixed
     */
    public function reset_password()
    {
        // If it's a POST request, we'll handle the password reset attempt
        if ($this->request->is('post')) {
            if (!setting('Auth.allowMagicLinkLogins')) {
                return redirect()->route('dt_admin/login')->with('error', lang('Auth.forgotDisabled'));
            }

            $users = model(PagesModel::class);

            // First things first - log the reset attempt.
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

            // Success! Save the new password, and cleanup the reset hash.
            $user->password = $this->request->getPost('password');
            $user->reset_hash = null;
            $user->reset_at = date('Y-m-d H:i:s');
            $user->reset_expires = null;
            $user->force_pass_reset = false;
            $users->save($user);

            return redirect()->route('dt_admin/login')->with('message', lang('Auth.resetSuccess'));
        }

        // If it's a GET request, we'll display the reset password form
        if (!setting('Auth.allowMagicLinkLogins')) {
            return redirect()->route('dt_admin/login')->with('error', lang('Auth.forgotDisabled'));
        }

        $token = $this->request->getGet('token');
        $data['title'] = lang('Auth.login');
        $data['token'] = $token;
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
