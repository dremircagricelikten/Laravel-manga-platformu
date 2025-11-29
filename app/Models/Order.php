<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'discount_amount',
        'final_amount',
        'payment_method',
        'payment_status',
        'paytr_token',
        'paytr_response',
        'bank_receipt',
        'bank_transfer_date',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'bank_transfer_date' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $with = ['items.coinPackage'];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->order_number = self::generateOrderNumber();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('payment_method', 'bank_transfer');
    }

    public function scopePayTR($query)
    {
        return $query->where('payment_method', 'paytr');
    }

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'paid',
            'approved_at' => now(),
        ]);

        // Add coins to user wallet
        $totalCoins = $this->items->sum(fn($item) => $item->coinPackage->coins * $item->quantity);
        $this->user->wallet()->increment('ki_balance', $totalCoins);
    }
}
