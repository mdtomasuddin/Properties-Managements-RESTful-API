<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use DevKandil\NotiFire\Traits\HasFcm;
use Dom\Document;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFcm, HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *hidden
     */
    protected $hidden = ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at', 'is_otp_verified', 'otp_expires_at', 'otp_verified_at', 'terms_and_conditions', 'phone_verified_at', 'email_verified_at', 'otp'];

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
     * Accessor for nid_file attribute
     * Accessor for getNidfileAttribute
     * @return array|null
     */
    public function getNidFileAttribute($value): ?array
    {
        $images = json_decode($value, true);
        if (! is_array($images)) {
            return [];
        }
        return array_map(function ($image) {
            // Check if the image is already a valid URL
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            // Check if it's an API request and convert relative path to a full URL
            if (request()->is('api/*') && ! empty($image)) {
                return url($image);
            }
            return $image;
        }, $images);
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
     * refferal code auto generate
     */
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

    /**
     * all relationship user to others table
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function financialAccounts()
    {
        return $this->hasMany(FinancialAccount::class);
    }

    public function category()
    {
        return $this->hasMany(Category::class);
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
        return $this->belongsToMany(Conversation::class, 'participants');
    }

    /**
     * Get the user's firebase tokens
     */
    public function firebaseTokens()
    {
        return $this->hasMany(FirebaseToken::class);
    }
}
