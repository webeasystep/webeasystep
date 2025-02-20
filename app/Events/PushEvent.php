<?php
// app/Events/OrderAccepted.php

namespace App\Events;

class PushEvent
{
    public static function trigger($notificationElements)
    {
        // Call the send_notification function from the NotificationService
        $notificationService = service('notificationService');
        $notificationService->send_notification($notificationElements);
    }
}
