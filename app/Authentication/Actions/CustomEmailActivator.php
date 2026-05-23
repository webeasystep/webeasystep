<?php

declare(strict_types=1);

namespace App\Authentication\Actions;

use CodeIgniter\Shield\Authentication\Actions\EmailActivator;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Exceptions\LogicException;
use CodeIgniter\Shield\Exceptions\RuntimeException;
use CodeIgniter\I18n\Time;

class CustomEmailActivator extends EmailActivator
{
    /**
     * Shows the initial screen to the user telling them
     * that an email was just sent to them with a link
     * to confirm their email address.
     */
    public function show(): string
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            throw new RuntimeException('Cannot get the pending login User.');
        }

        $userEmail = $user->email;
        if ($userEmail === null) {
            throw new LogicException(
                'Email Activation needs user email address. user_id: ' . $user->id
            );
        }

        $code = $this->createIdentity($user);

        /** @var \CodeIgniter\HTTP\IncomingRequest $request */
        $request = service('request');

        $ipAddress = $request->getIPAddress();
        $userAgent = (string) $request->getUserAgent();
        $date      = Time::now()->toDateTimeString();

        // Construct activation URL
        // We'll use a custom GET route for this: auth/a/activate?token=...
        $activationUrl = site_url('auth/a/activate') . '?token=' . $code;

        // Send the email
        helper('email');
        $email = emailer(['mailType' => 'html'])
            ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');
        $email->setTo($userEmail);
        $email->setSubject(lang('Auth.emailActivateSubject'));
        $email->setMessage($this->view(
            setting('Auth.views')['action_email_activate_email'],
            [
                'code'           => $code,
                'ipAddress'      => $ipAddress,
                'userAgent'      => $userAgent,
                'date'           => $date,
                'user'           => $user,
                'full_name'      => $user->full_name, // Pass full_name explicitly
                'activation_url' => $activationUrl,   // Pass activation_url
            ],
            ['debug' => false]
        ));

        if ($email->send(false) === false) {
            log_message(
                'error',
                'Activation email failed for user: ' . $user->email . "\n" . $email->printDebugger(['headers'])
            );

            session()->setFlashdata(
                'error',
                'تعذر إرسال رسالة التفعيل حاليا. برجاء المحاولة مرة أخرى لاحقا أو التواصل مع الدعم الفني.'
            );

            $email->clear();

            return $this->view(setting('Auth.views')['action_email_activate_show'], [
                'user' => $user,
                'token' => $code,
                'activation_url' => $activationUrl,
            ]);
        }

        // Clear the email
        $email->clear();

        // Display the info page
        return $this->view(setting('Auth.views')['action_email_activate_show'], [
            'user' => $user,
            'token' => $code,
            'activation_url' => $activationUrl,
        ]);
    }
    /**
     * Overriding the view method to bypass the custom helper in Common.php
     * and strictly use the renderer service with the provided path.
     */
    protected function view(string $view, array $data = [], array $options = []): string
    {
        return \Config\Services::renderer()
            ->setData($data, 'raw')
            ->render($view, $options);
    }
}
