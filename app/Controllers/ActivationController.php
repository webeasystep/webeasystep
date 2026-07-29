<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Exceptions\RuntimeException;

/**
 * Handles email activation verification for Shield.
 */
class ActivationController extends BaseController
{
    /**
     * Shows the activation form where users enter their token.
     */
    public function show()
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            return redirect()->to('/login')->with('error', lang('Auth.activateLinkExpired'));
        }

        $action = $authenticator->getAction();

        if ($action === null) {
            log_message('error', 'ActivationController: No pending activation action found for user ID ' . $user->id);

            return MainView('site_layout/shield/email_activate_show', [
                'user'  => $user,
                'error' => lang('Auth.errorSendingActivation', [$user->email ?? '']),
            ]);
        }

        try {
            return $action->show();
        } catch (\Throwable $e) {
            log_message('error', 'ActivationController: Failed to send activation email for user ID ' . $user->id . '. ' . $e->getMessage());

            session()->setFlashdata('error', lang('Auth.errorSendingActivation', [$user->email ?? '']));

            return MainView('site_layout/shield/email_activate_show', ['user' => $user]);
        }
    }

    /**
     * Verifies the activation token submitted by the user.
     */
    public function verify(): RedirectResponse
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $token = $this->request->getPost('token');

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            return redirect()->to('/login')->with('error', lang('Auth.activateLinkExpired'));
        }

        // Get the action class for email activation
        $action = auth('session')->getAction();
        
        if ($action === null) {
            return redirect()->to('/login')->with('error', lang('Auth.invalidActivateToken'));
        }

        // Get the identity for verification
        $identity = $this->getIdentity($user);

        if ($identity === null) {
            return redirect()->to('/login')->with('error', lang('Auth.invalidActivateToken'));
        }

        // Check if token matches
        if (! $authenticator->checkAction($identity, $token)) {
            session()->setFlashdata('error', lang('Auth.invalidActivateToken'));
            return redirect()->back();
        }

        // Activate the user
        $users = auth()->getProvider();
        $user->activate();
        $users->save($user);

        // Clean up activation identity
        $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);
        $identityModel->deleteIdentitiesByType($user, Session::ID_TYPE_EMAIL_ACTIVATE);

        // Log the user in
        $authenticator->login($user);

        // Success - redirect to login or dashboard
        return redirect()->to(config('Auth')->registerRedirect())
            ->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Get the identity for email activation.
     */
    private function getIdentity($user)
    {
        $identityModel = model(\CodeIgniter\Shield\Models\UserIdentityModel::class);
        
        return $identityModel->getIdentityByType(
            $user,
            Session::ID_TYPE_EMAIL_ACTIVATE
        );
    }
}
