<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Session\Session;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        $session = service('session');

        // Check if the user is logged in
        if (!$auth->loggedIn()) {
            // For AJAX requests, return JSON error
            if ($request->isAJAX() || $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Unauthorized', 'message' => 'You must be logged in']);
            }
            
            // Set flash data
            $session->setFlashdata('error', 'You must be logged in to view this page.');

            // Redirect to login page
            return redirect()->to('/dt_admin/login');
        }

        // Check if the user has admin rights
        if (!$auth->user()->inGroup('superadmin') && !$auth->user()->inGroup('admin')) {
            // For AJAX requests, return JSON error
            if ($request->isAJAX() || $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON(['error' => 'Forbidden', 'message' => 'You do not have permission']);
            }
            
            // Set flash data
            $session->setFlashdata('error', 'ليس لديك صلاحية للوصول إلى لوحة التحكم.');

            // Logout non-admin user and redirect to admin login
            $auth->logout();

            return redirect()->to('/dt_admin/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Usually nothing to do here
    }
}
