<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'itemable_type',
        'itemable_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Get the cart that owns the item
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the parent itemable model
     */
    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the subtotal for this cart item
     */
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
