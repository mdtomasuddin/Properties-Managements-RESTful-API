<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at', 'status', 'resident_id', 'user_id'];

    protected $casts = [
        'id'                       => 'integer',
        'user_id'                  => 'integer',
        'property_id'              => 'integer',
        'resident_id'              => 'integer',
        'auto_reminders'           => 'boolean',
        'reminder_note'            => 'string',
        'late_payment_fines'       => 'boolean',
        'reminder_schedule'        => 'string',
        'first_reminder_active'    => 'boolean',
        'due_date_reminder_active' => 'boolean',
        'overdue_notice_active'    => 'boolean',
        'status'                   => 'string',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
        'deleted_at'               => 'datetime',
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
}
