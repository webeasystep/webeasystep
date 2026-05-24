<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\UserIdentityModel;

/**
 * Handles email activation verification for Shield.
 */
class ActivationController extends BaseController
{
    /**
     * Shows the activation form where users enter their token.
     */
    public function show(): string
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            return redirect()->to('/login')->with('error', lang('Auth.activateLinkExpired'));
        }

        return MainView('site_layout/shield/email_activate_show', ['user' => $user]);
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

        // Get the authenticated user after successful verification
        $user = $authenticator->getUser();

        // Activate the user
        $authenticator->activateUser($user);

        // Success - redirect to login or dashboard
        return redirect()->to(config('Auth')->registerRedirect())
            ->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Activates the account via GET request (Link click).
     */
    public function activate(): RedirectResponse
    {
        $token = $this->request->getGet('token');
        if (! is_string($token) || trim($token) === '') {
            return redirect()->to('/login')->with('error', lang('Auth.activateLinkExpired'));
        }

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityBySecret(Session::ID_TYPE_EMAIL_ACTIVATE, $token);

        if ($identity === null) {
            return redirect()->to('/login')->with('error', lang('Auth.activateLinkExpired'));
        }

        $user = auth()->getProvider()->findById($identity->user_id);
        if ($user === null) {
            return redirect()->to('/login')->with('error', lang('Auth.activateLinkExpired'));
        }

        // If the user is already active, just make the link idempotent and clean any stale activation identities.
        if ((int) ($user->active ?? 0) !== 1) {
            $user->activate();
        }

        $identityModel->deleteIdentitiesByType($user, Session::ID_TYPE_EMAIL_ACTIVATE);

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
