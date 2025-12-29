<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{

    protected $table = 'notifications';
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'id'            => 'string',
        'type'          => 'string',
        'notifiable_id' => 'integer',
        'data'          => 'array',
    ];

    /**
     * Get the owning notifiable model.
     *
     * @return MorphTo
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
