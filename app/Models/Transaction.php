<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'transactionable_type',
        'transactionable_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent transactionable model
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include purchase transactions
     */
    public function scopePurchases($query)
    {
        return $query->where('type', 'purchase');
    }

    /**
     * Scope a query to only include spend transactions
     */
    public function scopeSpends($query)
    {
        return $query->where('type', 'spend');
    }
}
