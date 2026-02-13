<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MovieCast extends Model
{
    use HasFactory;
    protected $table = 'movie_cast';

    protected $fillable = [
        'movie_id',
        'movie_role_id',
        'full_name',
        'cast_name',
        'cover_image',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'movie_id' => 'integer',
        'movie_role_id' => 'integer',
        'full_name' => 'string',
        'cast_name' => 'string',
        'cover_image' => 'string',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'is_active' => 'boolean',
    ];
}
