<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; // ✅ HasApiTokens added for Sanctum

    protected $fillable = [
        'name', 'mobile', 'email', 'password', 'wallet_balance', 'diamond_points', 'referral_code', 'referred_by', 'role', 'status'
    ];

    /**
     * Automatically hash the password when setting it
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:2',
        'diamond_points' => 'integer',
        'email_verified_at' => 'datetime',
    ];
}
