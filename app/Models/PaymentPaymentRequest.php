<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPaymentRequest extends Model
{
    protected $table = 'payment_payment_request';

    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at'];

    protected $casts = [
        'id'                 => 'integer',
        'user_id'            => 'integer',
        'payment_id'         => 'integer',
        'property_id'        => 'integer',
        'payment_request_id' => 'integer',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];


    /**
     * Relationship with  (assuming a many-to-many relation)
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
