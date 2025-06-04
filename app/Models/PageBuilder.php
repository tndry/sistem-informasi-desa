<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageBuilder extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'content',
        'is_active',
    ];

    protected $casts = [
        'content' => 'json',
        'is_active' => 'boolean',
    ];
}
