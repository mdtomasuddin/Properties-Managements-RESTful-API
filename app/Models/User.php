<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'password'                => 'hashed',
        'name'                    => 'string',
        'email'                   => 'string',
        'phone'                   => 'string',
        'subscription'            => 'boolean',
        'role'                    => 'string',
        'status'                  => 'string',
        'avatar'                  => 'string',
        'lang'                    => 'string',
        'cover_photo'             => 'string',
        'address'                 => 'string',
        'national_id'             => 'string',
        'nid_file'                => 'array',
        'emergency_contact_name'  => 'string',
        'emergency_contact_phone' => 'string',
        'reffer_code'             => 'string',
        'invitation_code'         => 'string',
        'otp'                     => 'string',
    ];

    /**
     *
     */
    //getAvatarAttribute
    public function getAvatarAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        return filter_var($value, FILTER_VALIDATE_URL) ? $value : url($value);
    }

    // Accessor for cover_photo
    public function getCoverPhotoAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        return filter_var($value, FILTER_VALIDATE_URL) ? $value : url($value);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * getRouteKeyName
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'handle';
    }

    /**
     * sccessor for avater attribute
     * @param mixed $url
     * @return string
     */
    // public function getAvatarAttribute($url): string
    // {
    //     if ($url) {
    //         if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
    //             return $url;
    //         } else {
    //             return asset('storage/' . $url);
    //         }
    //     } else {
    //         return asset('assets/img/user_placeholder.png');
    //     }
    // }

    /**
     * profile
     * @return HasOne<Profile, User>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * otps
     * @return HasMany<OTP, User>
     */
    public function otps(): HasMany
    {
        return $this->hasMany(OTP::class);
    }

    // /**
    //  * Getting the role of the user
    //  *
    //  * @return BelongsTo<Role, User>
    //  */
    // public function role(): BelongsTo
    // {
    //     return $this->belongsTo(Role::class);
    // }

    //refferal code auto generate
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->reffer_code)) {
                do {
                    $code = mt_rand(100000, 999999);
                } while (self::where('reffer_code', $code)->exists());
                $user->reffer_code = $code;
            }
        });
    }
}
