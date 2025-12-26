<?php

namespace App\Notifications;

use DevKandil\NotiFire\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaintenanceNotification extends Notification
{

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
