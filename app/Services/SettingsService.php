<?php namespace App\Services;

use App\Libraries\Predis;
use CodeIgniter\Database\BaseConnection;

class SettingsService
{

    protected BaseConnection $db;

    public function __construct()
    {
        $this->db =  \Config\Database::connect();
    }


    function add_suggestions($order_id, $merchant_id)
    {

        if (setting('App.average_driver_suggestion') == 0) {
            $this->db->query("CALL sp_insert_order_suggestions({$order_id}, {$merchant_id}, 'merchant_orders/add_order')");
        } else {
            $query = $this->db->query("CALL sp_fetch_order_suggestions($order_id, {$merchant_id});");
            $all_drivers = $query->getResultArray();
            $this->db->close(); // To handle stored procedure results correctly

            if (!empty($all_drivers)) {
                // Initialize Redis
                $redis = Predis::getInstance()->getRedis();
                $pipe = $redis->pipeline();
                foreach ($all_drivers as $driver) {
                    $pipe->hSet('order_data:' . $order_id, $driver['driver_id'], $driver['accepted_orders_count']);
                }
                $pipe->execute();

                // Executing background task
                $cmd = "php index.php api/settings/suggestions_worker/$order_id";
                execInBackground($cmd); // You need to define this method
            }
        }
    }

    function suggestions_worker($order_id) {
        $redis = Predis::getInstance()->getRedis();

        // Getting all order IDs from the 'order_data' hash
        $allOrderIds = $redis->keys("order_data:$order_id");

        foreach ($allOrderIds as $orderKey) {
            $orderId = str_replace('order_data:', '', $orderKey);
            // Logging current order being processed
            //     log_message("error","Processing order ID: $orderId.");

            // Check if this order has already been accepted
            if ($redis->hExists('accepted_orders', $orderId)) {
                //  log_message("error","Order ID: $orderId is already accepted. Skipping.");
                continue;
            }

            $suggestedDrivers = $redis->hGetAll('order_data:' . $orderId);
            if (empty($suggestedDrivers)) {
                //  log_message("error","No suggested drivers found for order ID: $orderId. Skipping.");
                continue;
            }

            foreach ($suggestedDrivers as $driverId => $acceptedOrdersCount) {
                if ($redis->hExists('accepted_orders', $orderId)) {
                    //      log_message("error","Order ID: $orderId was accepted during processing. Breaking loop.");
                    break;
                }

                //   log_message("error","Adding driver ID: $driverId with count $acceptedOrdersCount for order ID: $orderId.");
                $this->add_driver_to_order_suggestions($orderId, $driverId);

                // $this->order->add_order_log($orderId, ORDER_PENDING, /* merchant_id? */, __FUNCTION__);

                sleep(setting('App.average_driver_suggestion')); // Waiting for 2 seconds
            }
        }

        $redis->del('order_data:' . $order_id);
    }

    function accept_order($orderId, $driver_id) {
        $redis = Predis::getInstance()->getRedis();
        // Set the key in Redis with an expiration of 15 minutes (900 seconds)
        if(!empty(setting('App.duration_between_two_accepted'))){
            $in_minute_duration = setting('App.duration_between_two_accepted');
            $this->set_driver_suggestion_hide($in_minute_duration,$driver_id);
        }
        if(setting('App.average_driver_suggestion') != 0){
            if ($orderId) {
                // Mark the order as accepted
                $redis->hSet('accepted_orders', $orderId, $driver_id);
                // Remove its data hash
                $redis->del('order_data:' . $orderId);
                log_message("error","Order {$orderId} has been marked as accepted.");
            } else {
                log_message("error","Invalid order ID provided.");
            }
        }

    }

    function add_driver_to_order_suggestions($order_id, $driver_id)
    {
        $data = [
            'order_id' => $order_id,
            'user_id' => $driver_id,
            'driver_response' => 1
        ];
        $this->db->table('tborders_drivers_suggestions')->insert($data);
        return $this->db->insertID();
    }
    function set_driver_suggestion_hide($in_minute_duration, $driver_id)
    {
        $this->db->table('users_drivers_details')
            ->set('hide_suggestion_at', 'DATE_ADD(NOW(), INTERVAL ' . intval($in_minute_duration) . ' MINUTE)', FALSE)
            ->where('user_id', $driver_id)
            ->update();
    }

}
