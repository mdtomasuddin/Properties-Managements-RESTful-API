<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{

    protected $guarded = [];

    protected $casts = [
        'question' => 'string',
        'answer'   => 'string',
        'status'   => 'string',
    ];

    protected $table = 'f_a_q_s';
}
