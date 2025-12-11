<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'id',
        'role_id',
        'permission',
        'permission_key',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'permission' => 'string',
        'permission_key' => 'string',
        'is_active' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
