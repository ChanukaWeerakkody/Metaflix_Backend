<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $table = 'movies';

    protected $fillable = [
        'id',
        'title',
        'sub_title',
        'rate',
        'quality',
        'duration',
        'country',
        'language_id',
        'category_id',
        'year',
        'subtitle_by',
        'description',
        'cover_image',
        'main_image',
    ];
    protected $casts = [
        'id'           => 'integer',
        'title'        => 'string',
        'sub_title'    => 'string',
        'rate'         => 'float',
        'quality'      => 'string',
        'duration'     => 'string',
        'country'      => 'string',
        'language_id'  => 'integer',
        'category_id'  => 'array',
        'year'         => 'integer',
        'subtitle_by'  => 'string',
        'description' => 'string',
        'cover_image'  => 'string',
        'main_image'   => 'string',
    ];
}
