<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'last_active_at',
        'is_commission_verified',
        'is_fee_verified',
        'commission',
        'fee',
        'country_id',
        'city_id',
        'country',
        'city'
    ];

    protected $casts = [
        'f_name' => 'string',
        'l_name' => 'string',
        'dial_country_code' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'image' => 'string',
        'type' => 'integer',
        'role' => 'integer',
        'password' => 'string',
        'is_phone_verified' => 'integer',
        'is_email_verified' => 'integer',
        'last_active_at' => 'datetime',
        'unique_id' => 'string',
        'referral_id' => 'string',
        'country' => 'string',
        'city' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function AauthAcessToken(): HasMany
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAgent($query): mixed
    {
        return $query->where('type', '=', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCustomer($query): mixed
    {
        return $query->where('type', '=', 2);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeMerchantUser($query): mixed
    {
        return $query->where('type', '=', 3);
    }

    /**
     * @param $query
     * @param $user_type
     * @return mixed
     */
    public function scopeOfType($query, $user_type): mixed
    {
        return $query->where('type', '=', $user_type);
    }

    /**
     * @return HasOne
     */
    public function emoney(): HasOne
    {
        return $this->hasOne(EMoney::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function user_log_histories(): HasMany
    {
        return $this->hasMany(UserLogHistory::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function merchant(): HasOne
    {
        return $this->hasOne(Merchant::class, 'user_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeGetAdmin($query)
    {
        return $query->where('is_kyc_verified', 0)->first();
    }
}
