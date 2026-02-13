<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    use HasFactory;
    protected $table = 'languages';

    protected $fillable = [
        'language',
        'is_active',
        'created_at',
        'updated_at',


    ];

    protected $casts = [
        'id' => 'integer',
        'language' => 'string',
        'is_active' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

    ];
}
