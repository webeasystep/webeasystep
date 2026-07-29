<?php

namespace App\Controllers;

use App\Libraries\UserType;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Authenticators\Session as SessionAuthenticator;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Models\UserModel;

class PasswordResetController extends BaseController
{
    protected string $identityType = 'password_reset';

    /**
     * Display the forgot password form (enter email).
     */
    public function forgotForm()
    {
        if (auth()->loggedIn()) {
            return redirect()->to(site_url(UserType::getDefaultPath(UserType::normalize(auth()->user()->user_type ?? null))));
        }

        $data['title'] = lang('Auth.forgotPassword');
        return MainView('site_layout/shield/forgot_password', $data);
    }

    /**
     * Handle the forgot password form submission.
     * Generates a 6-digit code and sends it via email.
     */
    public function sendCode(): RedirectResponse
    {
        $rules = [
            'email' => [
                'label' => lang('Auth.email'),
                'rules' => 'required|valid_email',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        
        /** @var UserModel $userModel */
        $userModel = auth()->getProvider();
        $user = $userModel->findByCredentials(['email' => $email]);

        if ($user === null) {
            // To prevent email enumeration, we show success even if email not found
            return redirect()->to(site_url('reset-password'))->with('message', 'إذا كان البريد الإلكتروني مسجلاً لدينا، فستصلك رسالة تحتوي على رمز إعادة التعيين.');
        }

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        // Delete any previous password reset identities
        $identityModel->deleteIdentitiesByType($user, $this->identityType);

        // Generate a 6-digit code
        helper('text');
        $code = random_string('numeric', 6);

        $identityModel->insert([
            'user_id' => $user->id,
            'type'    => $this->identityType,
            'secret'  => $code,
            'name'    => 'password_reset',
            'expires' => Time::now()->addSeconds(config('Auth')->passwordResetCodeLifetime ?? 3600)->format('Y-m-d H:i:s'),
        ]);

        // Send email
        $emailer = \Config\Services::email();
        $emailer->setFrom(env('MAIL_FROM_EMAIL', 'support@fakhrcs.com'), env('MAIL_FROM_NAME', 'FakhrCS'));
        $emailer->setTo($user->email);
        $emailer->setSubject(lang('Auth.forgotPassword'));
        
        $emailContent = MainView('site_layout/shield/Email/password_reset_email', ['code' => $code]);
        $emailer->setMessage($emailContent);

        if (! $emailer->send()) {
            log_message('error', 'Password Reset: Failed to send email to: ' . $user->email . '. Error: ' . $emailer->printDebugger());
            return redirect()->back()->withInput()->with('error', lang('Auth.errorSendingActivation', [$user->email]));
        }

        $emailer->clear();

        // Redirect to reset form with email pre-filled
        return redirect()->to(site_url('reset-password'))->withInput()->with('message', 'إذا كان البريد الإلكتروني مسجلاً لدينا، فستصلك رسالة تحتوي على رمز إعادة التعيين.');
    }

    /**
     * Display the reset password form (enter code and new password).
     */
    public function resetForm()
    {
        if (auth()->loggedIn()) {
            return redirect()->to(site_url(UserType::getDefaultPath(UserType::normalize(auth()->user()->user_type ?? null))));
        }

        $data['title'] = lang('Auth.resetPassword');
        return MainView('site_layout/shield/reset_password', $data);
    }

    /**
     * Handle the reset password form submission.
     * Verifies the code and updates the password.
     */
    public function resetPassword(): RedirectResponse
    {
        $rules = [
            'email'            => 'required|valid_email',
            'token'            => 'required|numeric|exact_length[6]',
            'password'         => 'required|strong_password',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $code  = $this->request->getPost('token');

        /** @var UserModel $userModel */
        $userModel = auth()->getProvider();
        $user = $userModel->findByCredentials(['email' => $email]);

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', lang('Auth.invalidActivateToken'));
        }

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityBySecret($this->identityType, $code);

        // Verify token exists and belongs to the user
        if ($identity === null || (int)$identity->user_id !== (int)$user->id) {
            return redirect()->back()->withInput()->with('error', lang('Auth.invalidActivateToken'));
        }

        // Verify token is not expired
        if (Time::now()->isAfter($identity->expires)) {
            return redirect()->back()->withInput()->with('error', lang('Auth.magicLinkExpired')); // Or a similar generic expired message
        }

        // Token is valid. Update password.
        $user->password = $this->request->getPost('password');
        
        // Remove force password reset flags if any
        if (property_exists($user, 'force_pass_reset')) {
            $user->force_pass_reset = false;
        }
        
        $userModel->save($user);

        // Delete the identity
        $identityModel->delete($identity->id);

        // Log the user in
        /** @var SessionAuthenticator $authenticator */
        $authenticator = auth('session')->getAuthenticator();
        $authenticator->login($user);

        // Redirect to dashboard
        $this->show_msg('success', 'نجاح', 'تم تغيير كلمة المرور بنجاح.');
        return redirect()->to(site_url(UserType::getDefaultPath(UserType::normalize($user->user_type ?? null))))->withCookies();
    }
}
