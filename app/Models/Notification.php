<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{

    use HasFactory;
    protected $guarded = [];

    protected $hidden = ['updated_at', 'notifiable_type', 'type'];

    //Casts Datatype
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
