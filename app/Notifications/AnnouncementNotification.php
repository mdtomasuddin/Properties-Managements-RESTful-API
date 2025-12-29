<?php

namespace App\Notifications;

use App\Helpers\Helper;
use App\Models\FirebaseToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementNotification extends Notification
{
    use Queueable;
    public $announcement;
    public $title;
    public $body;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
        $this->title        = 'Announcement-: ' . $announcement->category->name . ' at ' . $announcement->property->property_name . '. Title: ' . $announcement->title;
        $this->body         = 'Notices-' . $announcement->category->name . '-  Message : ' . $announcement->message;
    }

    public function via($notifiable)
    {
        // Get Firebase tokens of the notifiable user
        $tokens = FirebaseToken::where('user_id', $notifiable->id)->pluck('token')->toArray();

        if (! empty($tokens)) {
            Helper::send($tokens, $this->title, $this->body);
        }

        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'announcement_id' => $this->announcement->id,
            'category'        => $this->announcement->category_id,
            'type'            => 'announcement',
            'title'           => $this->title,
            'body'            => $this->body,
            'message'         => $this->announcement->message,
        ];
    }
}
