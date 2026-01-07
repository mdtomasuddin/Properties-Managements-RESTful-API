<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPaymentRequest extends Model
{
    protected $table = 'payment_payment_request';

    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function paymentRequest()
    {
        return $this->belongsTo(PaymentRequest::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
