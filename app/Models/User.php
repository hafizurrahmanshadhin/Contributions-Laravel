<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\Withdraw;
use App\Models\Collection;
use App\Models\FirebaseToken;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail {
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'otp',
        'is_otp_verified',
        'otp_verified_at',
        'otp_expires_at',
        'remember_token',
        'email_verified_at',
        'is_social',
        'deleted_at',
        'created_at',
        'updated_at',
        'role',
        'status',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'name'              => 'string',
        'email'             => 'string',
        'phone'             => 'string',
        'avatar'            => 'string',
        'is_otp_verified'   => 'boolean',
        'otp_verified_at'   => 'datetime',
        'otp_expires_at'    => 'datetime',
    ];

    public function collections(): HasMany {
        return $this->hasMany(Collection::class);
    }
    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function firebaseTokens(): HasMany {
        return $this->hasMany(FirebaseToken::class);
    }
}
