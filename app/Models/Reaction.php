<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reactionable_type',
        'reactionable_id',
        'user_id',
        'type',
    ];

    protected $with = ['user'];

    /**
     * Get the parent reactionable model (Series or Chapter).
     */
    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who reacted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get emoji for reaction type.
     */
    public function getEmojiAttribute(): string
    {
        return match($this->type) {
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😂',
            'wow' => '😮',
            'sad' => '😢',
            'angry' => '😡',
            default => '👍',
        };
    }

    /**
     * Available reaction types.
     */
    public static function types(): array
    {
        return [
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😂',
            'wow' => '😮',
            'sad' => '😢',
            'angry' => '😡',
        ];
    }
}
