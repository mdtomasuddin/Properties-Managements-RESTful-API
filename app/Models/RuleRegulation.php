<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleRegulation extends Model
{
    protected $guarded = [];
    protected $hidden  = ['updated_at', 'deleted_at', 'title', 'user_id', 'status'];

    protected $casts = [
        'id'          => 'integer',
        'user_id'     => 'integer',
        'property_id' => 'integer',
        'title'       => 'string',
        'description' => 'string',
        'status'      => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    // relationship class RuleRegulation extends Model to  others table
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
