<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Activation Controller to handle email activation without BaseController conflicts
 */
class ActivationController extends Controller
{
    protected $helpers = ['form', 'url'];

    public function initController(RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    /**
     * Show the activation form
     */
    public function show()
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();
        
        // Grab our action instance if one has been set.
        $action = $authenticator->getAction();

        if (empty($action)) {
            throw new PageNotFoundException();
        }

        return $action->show();
    }

    /**
     * Handle activation verification
     */
    public function verify()
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();
        
        // Grab our action instance if one has been set.
        $action = $authenticator->getAction();

        if (empty($action)) {
            throw new PageNotFoundException();
        }

        return $action->verify($this->request);
    }
}