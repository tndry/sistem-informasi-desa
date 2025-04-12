<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'excerpt',
        'content',
    ];
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function images()
    {
        return $this->hasMany(MediaLibrary::class);
    }
}
