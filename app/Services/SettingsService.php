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

    /* auto suggestion */
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
             //   $this->add_driver_to_order_suggestions($orderId, $driverId);

                // $this->order->add_order_log($orderId, ORDER_PENDING, /* merchant_id? */, __FUNCTION__);

                sleep(setting('App.average_driver_suggestion')); // Waiting for 2 seconds
            }
        }

        $redis->del('order_data:' . $order_id);
    }


}
