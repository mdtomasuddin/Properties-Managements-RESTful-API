<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPaymentRequest extends Model
{
    protected $table = 'payment_payment_request';

    protected $guarded = [];

    protected $hidden = ['updated_at', 'created_at'];


    public function payment_request()
    {
        return $this->belongsTo(PaymentRequest::class, 'payment_request_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
