<?php
namespace App\Notifications;

use App\Helpers\Helper;
use App\Models\FirebaseToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmergencyNotification extends Notification
{
    use Queueable;
    public $emergency;
    public $title;
    public $body;

    public function __construct($emergency)
    {
        $this->emergency = $emergency;
        $this->title     = '🚨Alert-: ' . $emergency->category->name . '. Location: ' . $emergency->location . ' at ' . $emergency->property->property_name;
        $this->body      = 'Notices-' . $emergency->description;
        // $this->body      = 'Notices-' . $emergency->category->name . '. Location: ' . $emergency->location . '. Description: ' . $emergency->description;
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
            'emergency_id' => $this->emergency->id,
            'category'     => $this->emergency->category_id,
            'type'         => 'emergency',
            'title'        => $this->title,
            'body'         => $this->body,
            'location'     => $this->emergency->location,
        ];
    }
}
