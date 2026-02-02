<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'Categories';

    protected $fillable = [
        'category_name',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',

    ];
    protected $casts = [
        'id' => 'integer',
        'category_name' => 'string',
        'is_active' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
}
