<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at'];

    //cast
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
        'plan_id' => 'integer',
        'tran_id' => 'string',
        'status'  => 'string',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
