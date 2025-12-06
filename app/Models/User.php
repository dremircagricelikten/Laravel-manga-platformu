<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_vip',
        'vip_expires_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_vip' => 'boolean',
        'vip_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's wallet
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get the user's transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the user's chapter unlocks
     */
    public function unlockedChapters(): HasMany
    {
        return $this->hasMany(ChapterUnlock::class);
    }

    /**
     * Get the user's cart
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the user's Ki balance (attribute accessor)
     */
    public function getKiBalanceAttribute(): float
    {
        return $this->wallet?->balance ?? 0;
    }

    /**
     * Get the user's Ki balance (method)
     */
    public function getKiBalance(): float
    {
        return $this->wallet?->balance ?? 0;
    }

    /**
     * Check if user has unlocked a chapter
     */
    public function hasUnlockedChapter(Chapter $chapter): bool
    {
        return $this->unlockedChapters()
            ->where('chapter_id', $chapter->id)
            ->exists();
    }

    /**
     * Check if user is currently VIP
     */
    public function isVip(): bool
    {
        if (!$this->is_vip) {
            return false;
        }

        if (!$this->vip_expires_at) {
            return true; // Permanent VIP
        }

        return now()->isBefore($this->vip_expires_at);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->hasRole('Super Admin') || $this->email === 'admin@admin.com';
    }
}
