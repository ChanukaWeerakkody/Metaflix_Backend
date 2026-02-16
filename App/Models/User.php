<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'role_id',
        'full_name',
        'contact_number',
        'email_address',
        'otp',
        'otp_reference',
        'status',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_reference',
    ];
    /**
     * Casts.
     */
    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'is_active' => 'integer',
        ];
    }

    /**
     * JWT identifier.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Custom JWT claims.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
