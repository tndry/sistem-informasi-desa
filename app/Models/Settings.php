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
        'deskripsi_desa',
        'alamat_desa',
        'telepon',
        'email',
        'social_media',
        'kode_pos',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'koordinat_lokasi',
    ];

    protected $casts = [
        'social_media' => 'json',
        'koordinat_lokasi' => 'json',
    ];
}
