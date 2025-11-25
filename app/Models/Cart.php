<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total price of cart
     */
    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Add item to cart
     */
    public function addItem($itemable, int $quantity = 1): CartItem
    {
        $existingItem = $this->items()
            ->where('itemable_type', get_class($itemable))
            ->where('itemable_id', $itemable->id)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            return $existingItem->fresh();
        }

        return $this->items()->create([
            'itemable_type' => get_class($itemable),
            'itemable_id' => $itemable->id,
            'quantity' => $quantity,
            'price' => $itemable->price,
        ]);
    }

    /**
     * Clear cart
     */
    public function clear(): void
    {
        $this->items()->delete();
    }
}
