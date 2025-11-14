<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at', 'created_at', 'status'];

    protected $casts = [
        'id'                  => 'integer',
        'user_id'             => 'integer',
        'property_id'         => 'integer',
        'bank_name'           => 'string',
        'branch'              => 'string',
        'account_number'      => 'string',
        'account_holder_name' => 'string',
        'status'              => 'string',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'deleted_at'          => 'datetime',
    ];

    /**
     * relationship class FinancialAccount extends Model to  others table
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
