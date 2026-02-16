<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'id',
        'role',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'role' => 'string',
        'is_active' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }
}
