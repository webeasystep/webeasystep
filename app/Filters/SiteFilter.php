<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SiteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Log filter execution
        log_message('debug', 'SITE_FILTER: Checking authentication for URI: ' . $request->getUri());
        
        $auth = service('auth');
        $session = service('session');

        // Debug session data
        $sessionData = $session->get();
        log_message('debug', 'SITE_FILTER: Session data: ' . json_encode($sessionData));
        log_message('debug', 'SITE_FILTER: Auth logged in check: ' . ($auth->loggedIn() ? 'true' : 'false'));

        // Check if the user is logged in
        if (!$auth->loggedIn()) {
            log_message('debug', 'SITE_FILTER: User not logged in');
            
            // For AJAX requests, return JSON error
            if ($request->isAJAX() || $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                log_message('debug', 'SITE_FILTER: AJAX request detected, returning JSON error');
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'User not authenticated']);
            }
            
            // Set flash data
            $session->setFlashdata('error', 'You must be logged in to view this page.');

            // Redirect to login page
            return redirect()->to('/login');
        }
        
        log_message('debug', 'SITE_FILTER: User authenticated, allowing request');
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Usually nothing to do here
    }
}
