<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterUnlock extends Model
{
    protected $fillable = [
        'user_id',
        'chapter_id',
        'unlocked_at',
        'cost_paid',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
        'cost_paid' => 'integer',
    ];

    /**
     * Get the user that owns the unlock
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the chapter that was unlocked
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Check if unlock was free
     */
    public function wasFree(): bool
    {
        return $this->cost_paid === 0;
    }
}
