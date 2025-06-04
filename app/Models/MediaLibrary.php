<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Testing\Fluent\Concerns\Has;

class MediaLibrary extends Model
{
    protected $table = 'media_library';

    use HasFactory;
    protected $fillable = [
        'section_item_id',
        'file_name',
        'file_path',
        'mime_type',
        'caption',
        'type',
        'file_size',
    ];

    public function sectionItem(): BelongsTo
    {
        return $this->belongsTo(SectionItem::class);
    }
}
