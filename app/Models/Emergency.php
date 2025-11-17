<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    protected $guarded = [];
    protected $hidden  = ['updated_at', 'deleted_at', 'emergency_type', 'status'];

    protected $casts = [
        'id'             => 'integer',
        'user_id'        => 'integer',
        'category_id'    => 'integer',
        'property_id'    => 'integer',
        'user_type'      => 'string',
        'emergency_type' => 'string',
        'description'    => 'string',
        'location'       => 'string',
        'status'         => 'string',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'deleted_at'     => 'datetime',
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
