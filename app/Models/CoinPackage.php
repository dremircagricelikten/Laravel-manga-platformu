<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'coin_amount',
        'price',
        'bonus_coins',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'coin_amount' => 'integer',
        'price' => 'decimal:2',
        'bonus_coins' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the total coins including bonus
     */
    public function getTotalCoinsAttribute(): int
    {
        return $this->coin_amount + $this->bonus_coins;
    }

    /**
     * Scope a query to only include active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }
}
