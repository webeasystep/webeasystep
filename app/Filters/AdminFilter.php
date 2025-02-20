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
            // Set flash data
            $session->setFlashdata('error', 'You must be logged in to view this page.');

            // Redirect to login page
            return redirect()->to('/dt_admin/login');
        }

        // Check if the user has admin rights
        // (Assuming 'admin' is a group name, modify as needed)
        if (!$auth->user()->inGroup('superadmin')) {
            // Set flash data
            $session->setFlashdata('error', 'You do not have permission to view this page.');

            // Redirect to a suitable page or show an error
            return redirect()->to('/dt_admin/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Usually nothing to do here
    }
}
