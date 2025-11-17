<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at', 'created_at', 'icon'];

    protected $casts = [
        'id'         => 'integer',
        'user_id'    => 'integer',
        'name'       => 'string',
        'icon'       => 'string',
        'type'       => 'string',
        'section'    => 'string',
        'status'     => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * relationship class Category extends Model to  others table
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
