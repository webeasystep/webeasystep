<?php

namespace App\Controllers;

use App\Libraries\UserType;
use App\Models\BaseModel;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use CodeIgniter\Shield\Authentication\Authenticators\Session as SessionAuthenticator;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Exceptions\ValidationException;
use CodeIgniter\Shield\Models\UserIdentityModel;
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
        $data['title']     = 'النجاح في SEU ليس أصعب... بل يحتاج للطريقة الصحيحة.';
        $data['meta_title'] = 'النجاح في SEU ليس أصعب... بل يحتاج للطريقة الصحيحة.';
        $data['meta_description'] = 'منصة تعليمية متخصصة لطلاب الجامعة السعودية الإلكترونية، تجمع كل ما تحتاجه للتفوق الأكاديمي في مكان واحد.';

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

    public function faqs()
    {
        $data['page_name'] = 'faqs';
        $data['title']     = 'الأسئلة الشائعة';
        $data['meta_title'] = 'الأسئلة الشائعة | فخر CS';
        $data['meta_description'] = 'إجابات على الأسئلة الشائعة حول فخر CS ومقررات الجامعة السعودية الإلكترونية.';
        
        $data['faqs'] = $this->db->table('tb_faqs')
            ->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();

        echo MainView('site_layout/faqs', $data);
    }

    public function preparatory()
    {
        $data['page_name'] = 'preparatory';
        $data['title']     = 'السنة الأولى المشتركة (التحضيرية)';

        // Get courses data from Courses controller
        $coursesController = new \Modules\Courses\Controllers\Courses();
        $allCourses = $coursesController->getCoursesForHome();
        
        // Filter out courses to only include those for Common First Year (college_id = 5)
        $filteredCourses = array_filter($allCourses, function($course) {
            return isset($course['college_id']) && $course['college_id'] == 5;
        });
        
        $data['courses'] = array_values($filteredCourses);
        
        $bundlesModel = new \Modules\Bundles\Models\BundlesModel();
        $data['bundles'] = $bundlesModel->getActiveBundles();

        echo MainView('site_layout/preparatory', $data);
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
            return redirect()->to('/login')->with('error', 'انتهت جلستك، سجّل دخولك من جديد ونكمل من حيث وقفت.');
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

    /**
     * Handle email activation link from verification email.
     * Redirects to the Shield activation verify form with the token.
     */
    public function activateAccount()
    {
        $token = $this->request->getGet('token');
        
        if (empty($token)) {
            return redirect()->to('/login')->with('error', lang('Auth.invalidActivateToken'));
        }
        
        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityBySecret(SessionAuthenticator::ID_TYPE_EMAIL_ACTIVATE, $token);
        
        if ($identity === null || empty($identity->user_id)) {
            return redirect()->to('/login')->with('error', lang('Auth.invalidActivateToken'));
        }

        /** @var UserModel $users */
        $users = auth()->getProvider();
        $user = $users->findById($identity->user_id);

        if (! $user instanceof User) {
            return redirect()->to('/login')->with('error', lang('Auth.activationNoUser'));
        }

        if (! $user->isActivated()) {
            $user->activate();
            $users->save($user);
        }

        $identityModel->deleteIdentitiesByType($user, SessionAuthenticator::ID_TYPE_EMAIL_ACTIVATE);

        /** @var SessionAuthenticator $authenticator */
        $authenticator = auth('session')->getAuthenticator();
        // #region debug-point activation-direct-login-session-state
        log_message('debug', 'ACTIVATION_DIRECT_LOGIN before session_state=' . json_encode(session(setting('Auth.sessionConfig')['field'])));
        // #endregion debug-point activation-direct-login-session-state

        // Clean up any pending session state to prevent Shield LogicException
        if ($authenticator->loggedIn() || session()->has(setting('Auth.sessionConfig')['field'])) {
            $authenticator->logout();
        }

        // #region debug-point activation-direct-login-session-cleaned
        log_message('debug', 'ACTIVATION_DIRECT_LOGIN after session_state=' . json_encode(session(setting('Auth.sessionConfig')['field'])));
        // #endregion debug-point activation-direct-login-session-cleaned

        $authenticator->login($user);

        $this->show_msg('success', 'يا هلا فيك', 'تفعل حسابك بنجاح، ودخلت مباشرة. حياك الله في اكاديمية فخر، ومكانك بيننا.');

        return redirect()->to(site_url(UserType::getDefaultPath(UserType::normalize($user->user_type ?? null))))->withCookies();
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    public function login()
    {
        log_message('debug', 'Site::login - Method: ' . $this->request->getMethod());
        log_message('debug', 'Site::login - POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Site::login - Already logged in: ' . (auth()->loggedIn() ? 'true' : 'false'));
        log_message('debug', 'Site::login - Request method check: ' . ($this->request->getMethod() === 'post' ? 'true' : 'false'));
        log_message('debug', 'Site::login - Request method uppercase check: ' . ($this->request->getMethod() === 'POST' ? 'true' : 'false'));

        // If it's a POST request, we'll handle the login attempt
        if (strtoupper($this->request->getMethod()) === 'POST') {
            log_message('debug', 'Site::login - Entering POST processing block');
            $rules = [
                'mobile' => 'required|egyptian_mobile',
                'password' => 'required',
            ];

            log_message('debug', 'Site::login - Validation rules: ' . json_encode($rules));
            log_message('debug', 'Site::login - Input data for validation: ' . json_encode($this->request->getPost()));

            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                log_message('debug', 'Site::login - Validation failed: ' . json_encode($errors));
                log_message('debug', 'Site::login - Validation error details: ' . print_r($errors, true));
                session()->setFlashdata('errors', $errors);
                return redirect()->back()->withInput();
            }

            log_message('debug', 'Site::login - Validation passed');

            // Get the credentials for login
            $remember = (bool)$this->request->getPost('remember');
            $credentials = [
                'mobile'    => $this->request->getPost('mobile'),
                'password' => $this->request->getPost('password')
            ];

            log_message('debug', 'Site::login - Attempting login with credentials: ' . json_encode(['mobile' => $credentials['mobile']]));
            log_message('debug', 'Site::login - Remember me: ' . ($remember ? 'true' : 'false'));

            $loginAttempt = auth()->remember($remember)->attempt($credentials);

            log_message('debug', 'Site::login - Login attempt completed');
            log_message('debug', 'Site::login - Login result isOK: ' . ($loginAttempt->isOK() ? 'true' : 'false'));

            if (!$loginAttempt->isOK()) {
                log_message('debug', 'Site::login - Login failed: ' . $loginAttempt->reason());
                log_message('debug', 'Site::login - Extra info: ' . json_encode($loginAttempt->extraInfo()));
                session()->setFlashdata('errors', [$loginAttempt->reason()]);
                return redirect()->back()->withInput();
            }

            log_message('debug', 'Site::login - Login successful for user ID: ' . auth()->user()->id);
            log_message('debug', 'Site::login - User logged in check: ' . (auth()->loggedIn() ? 'true' : 'false'));

            // Shield handles session management automatically, no need for manual $_SESSION variables
            // The session authenticator will store user data in session under the 'user' key

            $redirectURL = session('redirect_url') ?? site_url('/courses/my_courses');
            unset($_SESSION['redirect_url']);
            log_message('debug', 'Site::login - Redirecting to: ' . $redirectURL);
            $this->show_msg('success', 'يا هلا فيك', 'رجعت لمكانك، وإن شاء الله تلقى كل شيء أوضح وأسهل عليك.');
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


    //--------------------------------------------------------------------
    //  logout
    //--------------------------------------------------------------------
    /**
     * Log the user out.
     */

    public function logout(): RedirectResponse
    {
        auth()->logout();
        $this->show_msg('success', 'نراك على خير', lang('Auth.successLogout'));
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
            $email->setFrom(
                env('MAIL_FROM_EMAIL', env('MAIL_FROM_ADDRESS', 'support@fakhrcs.com')),
                env('MAIL_FROM_NAME', 'FakhrCS')
            );
            $email->setTo($user->email);
            $email->setSubject('مرحباً بك في FakhrCS - تم تفعيل حسابك بنجاح');

            // Load welcome email template
            $welcomeTemplate = view('site_layout/shield/Email/welcome_email', [
                'user' => $user,
                'siteName' => 'FakhrCS'
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
