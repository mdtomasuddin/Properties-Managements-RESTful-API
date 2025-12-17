<?php

namespace App\Notifications;

use DevKandil\NotiFire\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaintenanceNotification extends Notification
{
    // use Queueable;

    // public $maintenance;

    // /**
    //  * Create a new notification instance.
    //  *
    //  * @param $maintenance
    //  */
    // public function __construct($maintenance)
    // {
    //     $this->maintenance = $maintenance;
    // }

    // /**
    //  * Get the notification's delivery channels.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return array
    //  */
    // public function via($notifiable)
    // {
    //     return ['database', 'fcm'];
    // }

    // public function toArray($notifiable)
    // {
    //     return [
    //         'type' => 'maintenance',
    //         'title' => '',
    //         'maintenance_id' => $this->maintenance->id,
    //         'description' => $this->maintenance->description,
    //     ];
    // }
    // /**
    //  * Get the FCM representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return FcmMessage
    //  */
    // public function toFcm($notifiable)
    // {
    //     // Prepare the notification message
    //     return FcmMessage::create(
    //         'New Maintenance Request',                                      // Title
    //         'A new maintenance request has been created for your property.' // Body
    //     );
    // }

    use Queueable;
    public $maintenance;

    public function __construct($maintenance)
    {
        $this->maintenance = $maintenance;
    }

    public function via($notifiable)
    {
        // শুধু ডাটাবেস চ্যানেল ব্যবহার করছি কারণ পুশ আমরা ম্যানুয়ালি পাঠাব
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'maintenance_id' => $this->maintenance->id,
            'title' => 'New Maintenance Request',
            'body' => 'A new maintenance request has been created.',
            'type' => 'maintenance'
        ];
    }
}
