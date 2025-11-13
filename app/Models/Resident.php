<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at', 'created_at','status'];

    protected $casts = [
        'user_id'                      => 'integer',
        'property_id'                  => 'integer',
        'payment_frequency'            => 'string',
        'auto_rent_reminder'           => 'boolean',
        'monthly_rent'                 => 'decimal:2',
        'fixed_monthly_recurring_cost' => 'decimal:2',
        'due_amount'                   => 'decimal:2',
        'advance_paid_amount'          => 'decimal:2',
        'agreement_file'               => 'array',
        'tenant_file'                  => 'array',
        'unit_flat_number'             => 'string',
        'sms_invitation'               => 'string',
        'status'                       => 'string',
        'created_at'                   => 'datetime',
        'updated_at'                   => 'datetime',
        'deleted_at'                   => 'datetime',
    ];

    /**
     * Accessor for agreement_file
     * Already a full URLl.
     */
    public function getAgreementFileAttribute($value): array
    {
        $files = json_decode($value, true) ?: [];
        return array_map(function ($file) {
            if (filter_var($file, FILTER_VALIDATE_URL)) {
                return $file;
            }
            return url($file);
        }, $files);
    }

    /**
     * Accessor for tenant_file
     * Already a full URL.
     */
    public function getTenantFileAttribute($value): array
    {
        $files = json_decode($value, true) ?: [];
        return array_map(function ($file) {
            if (filter_var($file, FILTER_VALIDATE_URL)) {
                return $file;
            }
            return url($file);
        }, $files);
    }
}
