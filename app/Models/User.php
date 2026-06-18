<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Subscription;
use App\Models\Order;
use App\Models\RewardLedger;
use App\Models\Wishlist;
use App\Models\CartItem;
use App\Models\ProductReview;
use App\Models\BabyProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (blank($user->referral_code)) {
                $user->referral_code = self::generateUniqueReferralCode($user->name ?? 'NN');
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'referral_code',
        'referred_by',
        'phone',
        'date_of_birth',
        'gender',
        'avatar',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'date_of_birth' => 'date',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function rewardLedgers()
    {
        return $this->hasMany(RewardLedger::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function babyProfiles()
    {
        return $this->hasMany(BabyProfile::class);
    }

    public function babyProfile()
    {
        return $this->hasOne(BabyProfile::class);
    }

    public function ensureReferralCode(): string
    {
        if (filled($this->referral_code)) {
            return $this->referral_code;
        }

        $code = self::generateUniqueReferralCode($this->name ?? 'NN');
        $this->forceFill(['referral_code' => $code])->save();

        return $code;
    }

    public static function generateUniqueReferralCode(string $name): string
    {
        $prefix = Str::upper(Str::substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 4) ?: 'NN');

        do {
            $code = $prefix . Str::upper(Str::random(6));
        } while (self::query()->where('referral_code', $code)->exists());

        return $code;
    }
}
