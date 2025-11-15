<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at', 'created_at'];

    protected $casts = [
        'id'          => 'integer',
        'user_id'     => 'integer',
        'category_id' => 'integer',
        'property_id' => 'integer',
        'name'        => 'string',
        'file'        => 'string',
        'status'      => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    /**
     * relationship class Document extends Model to  others table
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
