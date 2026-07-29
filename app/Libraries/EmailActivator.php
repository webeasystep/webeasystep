<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Shield\Authentication\Actions\ActionInterface;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Entities\UserIdentity;
use CodeIgniter\Shield\Exceptions\LogicException;
use CodeIgniter\Shield\Exceptions\RuntimeException;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Throwable;

class EmailActivator implements ActionInterface
{
    private string $type = Session::ID_TYPE_EMAIL_ACTIVATE;
    private ?string $errorMessage = null;

    /**
     * Sends an activation email to the user.
     *
     * @param User $user
     *
     * @return bool
     */
    public function send(User $user): bool
    {
        $email = $user->email;

        if (empty($email)) {
            log_message('error', 'EmailActivator: User email is missing for activation. User ID: ' . $user->id);
            return false;
        }

        // Create the activation identity using the createIdentity method instead
        $code = $this->createIdentity($user);

        try {
            $emailer = \Config\Services::email();
            
            // Get email configuration from environment
            $fromEmail = env('MAIL_FROM_EMAIL', 'no-reply@msarlink.test');
            $fromName = env('MAIL_FROM_NAME', 'MSARLink System');
            
            $emailer->setFrom($fromEmail, $fromName);
            $emailer->setTo($email);
            $emailer->setSubject(lang('Auth.emailActivateSubject'));
            $emailer->setMessage($this->buildEmailMessage($user, $code));

            if (! $emailer->send(false)) {
                $this->setError('EmailActivator: Failed to send activation email to: ' . $email . '. Error: ' . $emailer->printDebugger());
                return false;
            }

            $emailer->clear();

            return true;
        } catch (Throwable $e) {
            $this->setError('EmailActivator: Exception sending activation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Set error message
     */
    private function setError(string $message): void
    {
        $this->errorMessage = $message;
        log_message('error', $message);
    }

    /**
     * Get error message
     */
    public function error(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * Build the email message for activation
     */
    protected function buildEmailMessage(User $user, $code): string
    {
        // Use the standard CodeIgniter renderer directly to bypass custom view function
        $renderer = \Config\Services::renderer();
        return $renderer->setData(['code' => $code], 'raw')
            ->render('site_layout/shield/Email/email_activate_email');
    }

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

        if (! $this->send($user)) {
            throw new RuntimeException(
                $this->error() ?? ('Cannot send email for user: ' . $user->email)
            );
        }

        // Display the info page
        return MainView('site_layout/shield/email_activate_show', ['user' => $user]);
    }

    /**
     * This method is unused.
     *
     * @return Response|string
     */
    public function handle(IncomingRequest $request)
    {
        throw new PageNotFoundException();
    }

    /**
     * Verifies the email address and code matches an
     * identity we have for that user.
     *
     * @return RedirectResponse|string
     */
    public function verify(IncomingRequest $request)
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $postedToken = $request->getVar('token');

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            throw new RuntimeException('Cannot get the pending login User.');
        }

        $identity = $this->getIdentity($user);

        // No match - let them try again.
        if (! $authenticator->checkAction($identity, $postedToken)) {
            session()->setFlashdata('error', lang('Auth.invalidActivateToken'));

            return MainView('site_layout/shield/email_activate_show');
        }

        $user = $authenticator->getUser();

        // Set the user active now
        $users = auth()->getProvider();
        $user->activate();
        $users->save($user);

        // Clean up activation identity
        $identityModel = model(UserIdentityModel::class);
        $identityModel->deleteIdentitiesByType($user, $this->type);

        $authenticator->login($user);

        // Success!
        return redirect()->to(config('Auth')->registerRedirect())
            ->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Creates an identity for the action of the user.
     *
     * @return string secret
     */
    public function createIdentity(User $user): string
    {
        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        // Delete any previous identities for action
        $identityModel->deleteIdentitiesByType($user, $this->type);

        $generator = static fn (): string => random_string('nozero', 6);

        return $identityModel->createCodeIdentity(
            $user,
            [
                'type'  => $this->type,
                'name'  => 'register',
                'extra' => lang('Auth.needVerification'),
            ],
            $generator
        );
    }

    /**
     * Returns an identity for the action of the user.
     */
    private function getIdentity(User $user): ?UserIdentity
    {
        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        return $identityModel->getIdentityByType(
            $user,
            $this->type
        );
    }

    /**
     * Returns the string type of the action class.
     */
    public function getType(): string
    {
        return $this->type;
    }
}
