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
     * Accessor to get live URL for the document file.
     */
    public function getFileAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        // If accessed via API, generate full URL
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*')) {
            return url($value);
        }
        return $value;
    }

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
