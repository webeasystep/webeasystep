<?php namespace App\Services;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Modules\Notifications\Models\NotificationsModel;

class NotificationService
{

    protected BaseConnection $db;
    protected NotificationsModel $notifications;

    public function __construct()
    {
        $this->db =  Database::connect();
        $this->notifications = new NotificationsModel();
    }


    /**
     * @param array $notification_elements
     * @return bool
     */
    function send_notification($notification_elements = array()): bool
    {

        if (empty($notification_elements)) {
            return false;
        }
        // get notification message
        $notification_data = $this->build_notification_message($notification_elements);

        // set the notifications log

        $this->notifications->save_notification_log($notification_data);

        ## handle push notification
        $user = $this->db->query("select user_type from users where id = {$notification_elements['recipient_id']} ")->getRow() ;
        if($user->user_type == DRIVER_USER){
            send_driver_push($notification_data);
        }else{
            send_push_notification($notification_data);
        }

        return true;
    }


    /**
     * @param array $notification_elements
     * @return array
     * @desc get notification data based on type (dynamic notification or sent title)
     */
    function build_notification_message($notification_elements = array()): array
    {

        $notification_data = array();
        ## get notification by name
        $sender = $this->get_sender($notification_elements['sender_id']);

        // match keywords with notifications message
        if(empty($notification_elements['static'])){
            $notification_type = $this->get_notification_type_by_name($notification_elements, $sender['notify_name']);
            $dynamic_message = $this->generate_dynamic_message($notification_elements,$notification_type, $sender['name']);
            $notification_data['title_ar'] = $dynamic_message;
            $notification_data['title_en'] = $dynamic_message;
            $notification_data['order_id'] = $notification_elements['order_id'];
            $notification_data['notification_id'] = $notification_type['id'];
        }else{
            $notification_data['title_ar'] = $notification_elements['notify_name'];
            $notification_data['title_en'] = $notification_elements['notify_name'];
        }
        $notification_data['priority'] = $notification_elements['priority'] ?? 0;
        $notification_data['sender_id'] = $notification_elements['sender_id'];
        $notification_data['recipient_id'] = $this->get_all_recipients($notification_elements);
        return $notification_data;
    }

    /**
     * @param $user_id
     */
    function get_sender($user_id)
    {
        $sender = array();
        $user = $this->db->query("select full_name,user_type from users WHERE id = $user_id ")->getRow();

        if ($user->user_type == 1) {
            $sender['notify_name'] = "admin_message";
        } elseif ($user->user_type == 2) {
            $sender['notify_name'] = "driver_message";
        } else {
            $sender['notify_name'] = "merchant_messages";
        }
        $sender['name'] = $user->full_name;
        if (!empty($sender)) {
            return $sender;
        }
        return false;
    }

    /**
     * @param $notification_elements
     * @param $notify_name
     * @return array
     */
    function get_notification_type_by_name($notification_elements, $notify_name): array
    {
        if (!empty($notification_elements['notify_name'])) {
            $notify_name = $notification_elements['notify_name'];
        }
        $notification = $this->db->query("
        select * from tbnotifications where notify_name = '$notify_name' ")->getRowArray();
        if (!empty($notification)) {
            return $notification;
        }
        return [];
    }

    /**
     * @param $notification_elements
     * @param $notification_type
     * @param $sender_name
     * @return array|string
     */
    function generate_dynamic_message($notification_elements,$notification_type, $sender_name)
    {
        $data_map = array();
        $data_map['{username}'] = $sender_name;
        $data_map['{site}'] = setting('App.site_title_en');
        $data_map['{message}'] = $notification_elements['message'] ?? "";
        // make replacement with array against data_map before send
        $message_ar = str_replace(array_keys($data_map), array_values($data_map), $notification_type['title_ar']);
        if (is_array($message_ar)) {
            $message = implode(" ", $message_ar);
        } else {
            $message = $message_ar;
        }

        return $message;
    }

    /**
     * @param $notification_elements
     * @return mixed
     */
    function get_all_recipients($notification_elements)
    {
        // check if send to a recipient or all users
        if (!empty($notification_elements['recipient_id'])) {
            $recipients = $notification_elements['recipient_id'];
        } else {
            $users = $this->db->query("select id from users where active = 1")->getResultArray();
            $recipients = array_column($users, 'id');
        }
        return $recipients;
    }

}
