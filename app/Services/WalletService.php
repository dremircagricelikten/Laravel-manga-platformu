<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Get or create wallet for user
     */
    public function getOrCreateWallet(User $user): Wallet
    {
        return $user->wallet ?? Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
    }

    /**
     * Check if user has sufficient balance
     */
    public function hasSufficientBalance(User $user, float $amount): bool
    {
        $wallet = $this->getOrCreateWallet($user);
        return $wallet->hasSufficientBalance($amount);
    }

    /**
     * Add coins to user's wallet
     */
    public function add(
        User $user,
        float $amount,
        string $description,
        ?Model $relatedModel = null
    ): Transaction {
        return DB::transaction(function () use ($user, $amount, $description, $relatedModel) {
            $wallet = $this->getOrCreateWallet($user);
            $wallet->increment('balance', $amount);
            $wallet->refresh();

            return $this->createTransaction(
                user: $user,
                type: 'purchase',
                amount: $amount,
                balanceAfter: $wallet->balance,
                description: $description,
                relatedModel: $relatedModel
            );
        });
    }

    /**
     * Deduct coins from user's wallet
     */
    public function deduct(
        User $user,
        float $amount,
        string $description,
        ?Model $relatedModel = null
    ): Transaction {
        return DB::transaction(function () use ($user, $amount, $description, $relatedModel) {
            $wallet = $this->getOrCreateWallet($user);

            if (!$wallet->hasSufficientBalance($amount)) {
                throw new \Exception('Insufficient balance');
            }

            $wallet->decrement('balance', $amount);
            $wallet->refresh();

            return $this->createTransaction(
                user: $user,
                type: 'spend',
                amount: -$amount,
                balanceAfter: $wallet->balance,
                description: $description,
                relatedModel: $relatedModel
            );
        });
    }

    /**
     * Admin adjustment to wallet
     */
    public function adjust(
        User $user,
        float $amount,
        string $description,
        ?Model $relatedModel = null
    ): Transaction {
        return DB::transaction(function () use ($user, $amount, $description, $relatedModel) {
            $wallet = $this->getOrCreateWallet($user);

            if ($amount > 0) {
                $wallet->increment('balance', $amount);
            } else {
                $wallet->decrement('balance', abs($amount));
            }

            $wallet->refresh();

            return $this->createTransaction(
                user: $user,
                type: 'admin_adjustment',
                amount: $amount,
                balanceAfter: $wallet->balance,
                description: $description,
                relatedModel: $relatedModel
            );
        });
    }

    /**
     * Create a transaction record
     */
    private function createTransaction(
        User $user,
        string $type,
        float $amount,
        float $balanceAfter,
        string $description,
        ?Model $relatedModel = null
    ): Transaction {
        return Transaction::create([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'transactionable_type' => $relatedModel ? get_class($relatedModel) : null,
            'transactionable_id' => $relatedModel?->id,
        ]);
    }
}
