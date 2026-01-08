<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at'];

    protected $casts = [
        'id'                   => 'integer',
        'user_id'              => 'integer',
        'property_id'          => 'integer',
        'category_id'          => 'integer',
        'send_to'              => 'string',
        'resident_id'          => 'integer',
        'attachments'          => 'array',
        'amount'               => 'decimal:2',
        'description'          => 'string',
        'due_date'             => 'date',
        'status'               => 'string',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
        'deleted_at'           => 'datetime',
    ];
    /**
     * Accessor to retrieve attachment
     * paths as live URLs (for images, PDFs, etc.).
     */
    public function getAttachmentsAttribute($value): ?array
    {
        $attachments = json_decode($value, true);
        if (! is_array($attachments)) {
            return [];
        }
        return array_map(function ($attachment) {
            if (filter_var($attachment, FILTER_VALIDATE_URL)) {
                return $attachment;
            }
            if (request()->is('api/*') && ! empty($attachment)) {
                return url($attachment);
            }
            return $attachment;
        }, $attachments);
    }

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
    public function payments()
    {
        return $this->hasMany(PaymentPaymentRequest::class);
    }
}
