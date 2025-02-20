<?php

namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\HTTP\RequestInterface;
use Config\Database;
use Config\Services;

class BaseModel extends Model
{
    /**
     * Instance of the main Request object.
     */
    protected $request;

    /**
     * Database connection instance.
     */
    protected $db;

    public function __construct(RequestInterface $request = null)
    {
        // If request is not provided, use the shared request instance
        $this->request = Services::request();
        // Initialize the database connection
        $this->db = Database::connect();

        parent::__construct();
    }
}
