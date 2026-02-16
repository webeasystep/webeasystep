<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Language\Language;
use CodeIgniter\Session\Session;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

use Config\Database;
use Config\Services;
use Psr\Log\LoggerInterface;

class BaseApiController extends ResourceController
{
    protected $helpers = [ 'url', 'function', 'form', "validation",  'utils'];

    public Session $session;
    public Language $language;
    public BaseConnection $db;

    public function initController( $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->db = Database::connect();
        $this->session = Services::session();
        $this->language = Services::language();

    }


}
