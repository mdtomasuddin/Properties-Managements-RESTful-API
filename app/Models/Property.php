<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at', 'created_at', 'status'];

    protected $casts = [
        'id'                 => 'integer',
        'user_id'            => 'integer',
        'property_type'      => 'string',
        'property_name'      => 'string',
        'property_address'   => 'string',
        'number_of_units'    => 'integer',
        'documents'          => 'array',
        'security_contacts'  => 'string',
        'additional_details' => 'string',
        'status'             => 'string',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
        'deleted_at'         => 'datetime',
    ];

    /**
     * Accessor to retrieve document paths
     *  live links (URLs).
     * image & pdf both
     */
    public function getDocumentsAttribute($value): ?array
    {
        $documents = json_decode($value, true);
        if (! is_array($documents)) {
            return [];
        }
        return array_map(function ($document) {
            if (filter_var($document, FILTER_VALIDATE_URL)) {
                return $document;
            }
            if (request()->is('api/*') && ! empty($document)) {
                return url($document); // Generates the full URL
            }
            return $document;
        }, $documents);
    }

    /**
     * relationship property to  others table
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
    public function emergeny()
    {
        return $this->hasMany(Emergency::class);
    }
    public function paymentrequest()
    {
        return $this->hasMany(PaymentRequest::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
    public function paymentReminder()
    {
        return $this->hasMany(PaymentReminder::class);
    }

    //message relationship
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}
