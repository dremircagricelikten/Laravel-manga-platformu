<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'coin_package_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    protected $with = ['coinPackage'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coinPackage(): BelongsTo
    {
        return $this->belongsTo(CoinPackage::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->coinPackage->price * $this->quantity;
    }

    public static function getTotalAmount(int $userId): float
    {
        return static::where('user_id', $userId)
            ->get()
            ->sum('subtotal');
    }

    public static function getItemCount(int $userId): int
    {
        return static::where('user_id', $userId)->sum('quantity');
    }
}
