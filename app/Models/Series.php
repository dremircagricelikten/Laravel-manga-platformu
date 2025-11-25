<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Series extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'slug',
        'description',
        'cover_image',
        'status',
        'author',
        'artist',
        'is_nsfw',
        'is_featured',
        'views',
        'alternative_titles',
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
        'is_nsfw' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the categories for the series
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_series');
    }

    /**
     * Get the volumes for the series
     */
    public function volumes(): HasMany
    {
        return $this->hasMany(Volume::class)->orderBy('volume_number');
    }

    /**
     * Get the chapters for the series
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }

    /**
     * Scope a query to only include series of a given type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include published series
     */
    public function scopePublished($query)
    {
        return $query->whereHas('chapters', function ($q) {
            $q->where('is_published', true)
                ->where('published_at', '<=', now());
        });
    }

    /**
     * Scope a query to only include manga
     */
    public function scopeManga($query)
    {
        return $query->where('type', 'manga');
    }

    /**
     * Scope a query to only include novels
     */
    public function scopeNovel($query)
    {
        return $query->where('type', 'novel');
    }

    /**
     * Scope a query to only include anime
     */
    public function scopeAnime($query)
    {
        return $query->where('type', 'anime');
    }
}
