<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Volume extends Model
{
    protected $fillable = [
        'series_id',
        'volume_number',
        'title',
        'description',
        'cover_image',
    ];

    protected $casts = [
        'volume_number' => 'integer',
    ];

    /**
     * Get the series that owns the volume
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the chapters for the volume
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }
}
