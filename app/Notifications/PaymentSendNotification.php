<?php
namespace App\Notifications;

use App\Helpers\Helper;
use App\Models\FirebaseToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentSendNotification extends Notification
{
    use Queueable;

    public $payment;
    public $title;
    public $body;

    public function __construct($payment)
    {
        $this->payment = $payment;
        $this->title   = 'Payment-:' . ' at property name: ' . $payment->property->property_name . '.- total_amount : ' . $payment->total_amount . 'Taka.';
        $this->body    = 'Resident name: ' . $payment->user->name . ' at property name: ' . $payment->property->property_name . ' .- total_amount : ' . $payment->total_amount . 'Taka.';
    }

    public function via($notifiable)
    {
        // Get Firebase tokens Get Firebase tokens of the notifiable user
        $tokens = FirebaseToken::where('user_id', $notifiable->id)->pluck('token')->toArray();

        if (! empty($tokens)) {
            Helper::send($tokens, $this->title, $this->body);
        }
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'payment_id'  => $this->payment->id,
            'type'        => 'payment',
            'title'       => $this->title,
            'body'        => $this->body,
            'amount'      => $this->payment->total_amount,
            'method'      => $this->payment->payment_method,
            'resident_id' => $this->payment->user_id,
            'property_id' => $this->payment->property_id,
            'status'      => $this->payment->status,
        ];
    }
}
