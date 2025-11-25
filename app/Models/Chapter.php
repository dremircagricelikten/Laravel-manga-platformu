<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Chapter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'series_id',
        'volume_id',
        'chapter_number',
        'title',
        'slug',
        'images',
        'content',
        'video_embed',
        'is_premium',
        'unlock_cost',
        'lock_duration_days',
        'published_at',
        'is_published',
        'views_count',
    ];

    protected $casts = [
        'images' => 'array',
        'is_premium' => 'boolean',
        'is_published' => 'boolean',
        'unlock_cost' => 'integer',
        'lock_duration_days' => 'integer',
        'published_at' => 'datetime',
        'free_at' => 'datetime',
        'views_count' => 'integer',
        'chapter_number' => 'decimal:2',
    ];

    /**
     * Boot the model
     */
    protected static function booted()
    {
        static::creating(function ($chapter) {
            $chapter->calculateFreeAt();
        });

        static::updating(function ($chapter) {
            if ($chapter->isDirty(['published_at', 'lock_duration_days'])) {
                $chapter->calculateFreeAt();
            }
        });
    }

    /**
     * Calculate the free_at timestamp based on published_at and lock_duration
     */
    public function calculateFreeAt(): void
    {
        if ($this->published_at && $this->lock_duration_days > 0) {
            $this->free_at = Carbon::parse($this->published_at)
                ->addDays($this->lock_duration_days);
        } else {
            $this->free_at = $this->published_at;
        }
    }

    /**
     * Check if the chapter is currently locked
     */
    public function isLocked(): bool
    {
        if (!$this->is_premium) {
            return false;
        }

        if (!$this->free_at) {
            return true; // Premium with no free date
        }

        return now()->isBefore($this->free_at);
    }

    /**
     * Check if the chapter is free to access
     */
    public function isFree(): bool
    {
        return !$this->isLocked();
    }

    /**
     * Check if a user can access this chapter
     */
    public function canBeAccessedBy(?User $user): bool
    {
        // Not published yet
        if (!$this->is_published || $this->published_at?->isFuture()) {
            return false;
        }

        // Free content
        if ($this->isFree()) {
            return true;
        }

        // Guest cannot access locked content
        if (!$user) {
            return false;
        }

        // Check if user has unlocked
        return $this->unlocks()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Increment views count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get the series that owns the chapter
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the volume that owns the chapter
     */
    public function volume(): BelongsTo
    {
        return $this->belongsTo(Volume::class);
    }

    /**
     * Get the unlocks for the chapter
     */
    public function unlocks(): HasMany
    {
        return $this->hasMany(ChapterUnlock::class);
    }

    /**
     * Scope a query to only include published chapters
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include locked chapters
     */
    public function scopeLocked($query)
    {
        return $query->where('is_premium', true)
            ->where(function ($q) {
                $q->whereNull('free_at')
                    ->orWhere('free_at', '>', now());
            });
    }

    /**
     * Scope a query to only include free chapters
     */
    public function scopeFree($query)
    {
        return $query->where(function ($q) {
            $q->where('is_premium', false)
                ->orWhere('free_at', '<=', now());
        });
    }
}
