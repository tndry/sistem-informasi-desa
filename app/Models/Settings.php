<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_desa',
        'logo_desa',
        'background_image',
        'color_primary',
        'color_secondary',
    ];
}
