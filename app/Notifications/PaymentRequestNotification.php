<?php

namespace App\Notifications;

use App\Helpers\Helper;
use App\Models\FirebaseToken;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentRequestNotification extends Notification
{
    use Queueable;

    public $paymentRequest;
    public $title;
    public $body;

    public function __construct($paymentRequest)
    {
        $this->paymentRequest = $paymentRequest;
        $this->title          = 'Payment Request-: ' . $paymentRequest->category->name . ' at ' . $paymentRequest->property->property_name . '.- Amount: ' . $paymentRequest->amount . ' Due Date: ' . $paymentRequest->due_date;
        $this->body           = 'Payment' . $paymentRequest->category->name . ' Amount: ' . $paymentRequest->amount . 'Taka.' . ' Due Date: ' . Carbon::parse($paymentRequest->due_date)->format('d M, Y') . '-  Description :' . $paymentRequest->description;
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
            'payment_request_id' => $this->paymentRequest->id,
            'category'           => $this->paymentRequest->category_id,
            'type'               => 'payment_request',
            'title'              => $this->title,
            'body'               => $this->body,
            'description'        => $this->paymentRequest->description,
            'amount'             => $this->paymentRequest->amount,
            'due_date'           => $this->paymentRequest->due_date,
        ];
    }
}
