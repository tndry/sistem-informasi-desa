<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class MediaLibrary extends Model
{
    use HasFactory;
    protected $fillable = [
        'section_item_id',
        'file_name',
        'file_path',
        'caption',
        'type'
    ];

    public function sectionItem()
    {
        return $this->belongsTo(SectionItem::class);
    }
}
