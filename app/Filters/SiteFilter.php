<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Session\Session;

class SiteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        $session = service('session');

        // Check if the user is logged in
        if (empty($_SESSION["user_id"])) {
            // Set flash data
            $session->setFlashdata('error', 'You must be logged in to view this page.');
            // Redirect to login page
            return redirect()->to('site/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Usually nothing to do here
    }
}
