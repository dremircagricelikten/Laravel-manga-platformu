<?php

namespace App\Services;

use App\Models\User;
use App\Models\Chapter;
use App\Models\ChapterUnlock;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\ChapterAlreadyUnlockedException;
use Illuminate\Support\Facades\DB;

class UnlockChapterService
{
    public function __construct(
        private WalletService $walletService
    ) {
    }

    /**
     * Unlock a chapter for a user
     *
     * @throws InsufficientBalanceException
     * @throws ChapterAlreadyUnlockedException
     */
    public function unlock(User $user, Chapter $chapter): ChapterUnlock
    {
        // Check if already unlocked
        if ($user->hasUnlockedChapter($chapter)) {
            throw new ChapterAlreadyUnlockedException(
                "You have already unlocked this chapter."
            );
        }

        // Check if chapter is free
        if ($chapter->isFree()) {
            return $this->createFreeUnlock($user, $chapter);
        }

        // Check balance
        $cost = $chapter->unlock_cost;
        if (!$this->walletService->hasSufficientBalance($user, $cost)) {
            throw new InsufficientBalanceException(
                "Insufficient Ki balance. Required: {$cost} Ki"
            );
        }

        return DB::transaction(function () use ($user, $chapter, $cost) {
            // Deduct coins
            $this->walletService->deduct(
                user: $user,
                amount: $cost,
                description: "Unlocked Chapter {$chapter->chapter_number}: {$chapter->title}",
                relatedModel: $chapter
            );

            // Create unlock record
            return ChapterUnlock::create([
                'user_id' => $user->id,
                'chapter_id' => $chapter->id,
                'unlocked_at' => now(),
                'cost_paid' => $cost,
            ]);
        });
    }

    /**
     * Create a free unlock record
     */
    private function createFreeUnlock(User $user, Chapter $chapter): ChapterUnlock
    {
        return ChapterUnlock::create([
            'user_id' => $user->id,
            'chapter_id' => $chapter->id,
            'unlocked_at' => now(),
            'cost_paid' => 0,
        ]);
    }

    /**
     * Check if user can unlock a chapter
     */
    public function canUnlock(User $user, Chapter $chapter): array
    {
        if ($user->hasUnlockedChapter($chapter)) {
            return [
                'can_unlock' => false,
                'reason' => 'already_unlocked',
                'message' => 'You have already unlocked this chapter.',
            ];
        }

        if ($chapter->isFree()) {
            return [
                'can_unlock' => true,
                'reason' => 'free',
                'cost' => 0,
            ];
        }

        $cost = $chapter->unlock_cost;
        $balance = $user->getKiBalance();

        if ($balance < $cost) {
            return [
                'can_unlock' => false,
                'reason' => 'insufficient_balance',
                'required' => $cost,
                'current' => $balance,
                'needed' => $cost - $balance,
            ];
        }

        return [
            'can_unlock' => true,
            'reason' => 'premium',
            'cost' => $cost,
        ];
    }

    /**
     * Bulk unlock chapters
     */
    public function bulkUnlock(User $user, array $chapterIds): array
    {
        $chapters = Chapter::whereIn('id', $chapterIds)->get();
        $results = [];

        foreach ($chapters as $chapter) {
            try {
                $unlock = $this->unlock($user, $chapter);
                $results[] = [
                    'chapter_id' => $chapter->id,
                    'status' => 'success',
                    'unlock' => $unlock,
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'chapter_id' => $chapter->id,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
