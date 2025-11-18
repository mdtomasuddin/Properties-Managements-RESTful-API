<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $guarded = [];

    protected $casts = [
        'id'                 => 'integer',
        'user_id'            => 'integer',
        'resident_id'        => 'integer',
        'property_id'        => 'integer',
        'payment_request_id' => 'integer',
        'receipt_number'     => 'string',
        'payment_items'      => 'array',
        'total_amount'       => 'decimal:2',
        'payment_date'       => 'date',
        'payment_method'     => 'string',
        'account_name'       => 'string',
        'account_number'     => 'string',
        'bank_name'          => 'string',
        'branch_name'        => 'string',
        'swift_code'         => 'string',
        'status'             => 'string',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function residents()
    {
        return $this->belongsToMany(Resident::class);
    }
}
