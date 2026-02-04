<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieRole extends Model
{
    use HasFactory;
    protected $table = 'movie_roles';

    protected $fillable = ['role', 'is_active', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    protected $casts = [
        'id' => 'integer',
        'role' => 'string',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
}
