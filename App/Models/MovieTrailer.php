<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieTrailer extends Model
{
    use HasFactory;

    protected $table = 'movie_trailers';

    protected $fillable = [
        'movie_id',
        'trailer',
        'size',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'movie_id' => 'integer',
        'trailer' => 'string',
        'size' => 'string',
        'is_active' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
}
