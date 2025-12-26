<?php

namespace App\Notifications;

use App\Helpers\Helper;
use App\Models\FirebaseToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaintenanceNotification extends Notification
{

    use Queueable;

    public $maintenance;
    public $title;
    public $body;

    //constructor
    public function __construct($maintenance)
    {
        $this->maintenance = $maintenance;
        $this->title       = 'New Maintenance Request for-' . $maintenance->property->property_name;
        $this->body        = 'A new maintenance request has been created for-Name: ' . $maintenance->user->name . '. Description: ' . $maintenance->description;
    }

    //channels to send notification
    public function via($notifiable)
    {
        $tokens = FirebaseToken::where('user_id', $notifiable->id)->pluck('token')->toArray();
        if (! empty($tokens)) {
            Helper::send($tokens, $this->title, $this->body); //send push notification
        }
        return ['database']; //database insert notification
    }

    //data to store in database
    public function toArray($notifiable)
    {
        return [
            'maintenance_id' => $this->maintenance->id,
            'type'           => 'maintenance',
            'title'          => $this->title,
            'body'           => $this->body,
        ];
    }
}
