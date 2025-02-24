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
    public function get_client($mobile)
    {
        $builder = $this->db->table("fd_orders as orders"); // Alias the fd_orders table as 'orders'
        return $builder->select("order_code, users.mobile, users.full_name, 
                             fd_clients_addresses.title, 
                             fd_clients_addresses.address, 
                             orders.id as order_id,
                             CONCAT('https://www.google.com/maps?q=', ST_Y(fd_clients_addresses.location), ',', ST_X(fd_clients_addresses.location)) AS location, 
                             tbareas.area_name")
            ->join('users', 'orders.client_id = users.id') // Join the 'users' table
            ->join('fd_clients_addresses', 'fd_clients_addresses.id = orders.address_id') // Join the 'fd_clients_addresses' table
            ->join('tbareas', 'tbareas.id = fd_clients_addresses.area_id') // Join the 'tbareas' table
            ->where('users.user_type', 'client')
          //  ->whereNotIn('orders.order_status', [4, 5]) // Exclude order_status 4 and 5
            ->where('users.mobile', $mobile)
          //  ->where('orders.created_at >=', 'DATE_SUB(NOW(), INTERVAL 2 HOUR)', false)
            ->get()
            ->getRow();
    }

    public function take_order($driver_id, $order_id)
    {
        $builder = $this->db->table("fd_orders");
        // Update the order status to 3 and assign the driver
        $builder->set([
            'order_status' => 3,
            'driver_id' => $driver_id
        ])->where('id', $order_id)
            ->update();
    }
}
