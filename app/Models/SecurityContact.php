<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityContact extends Model
{

    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at', 'created_at', 'status'];
    //cast
    protected $casts = [
        'id'          => 'integer',
        'phone'       => 'string',
        'description' => 'string',
        'property_id' => 'integer',
        'category_id' => 'integer',
        'user_id'     => 'integer',
        'status'      => 'string',

    ];

    //relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
