<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Services\UnlockChapterService;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    protected $unlockService;

    public function __construct(UnlockChapterService $unlockService)
    {
        $this->unlockService = $unlockService;
    }

    public function read($slug)
    {
        $chapter = Chapter::where('slug', $slug)
            ->with('series')
            ->firstOrFail();

        // Check if chapter is published
        if ($chapter->published_at > now()) {
            abort(404);
        }

        // Check if user has unlocked this chapter
        $isUnlocked = false;
        if (auth()->check()) {
            $isUnlocked = auth()->user()->unlockedChapters()
                ->where('chapter_id', $chapter->id)
                ->exists();
        }

        // If premium and not unlocked, check time-based access
        if ($chapter->is_premium && !$isUnlocked) {
            $freeAccessTime = $chapter->published_at->addDays($chapter->free_access_days ?? 0);
            if (now() >= $freeAccessTime) {
                $isUnlocked = true;
            }
        }

        // Get previous and next chapters
        $previousChapter = Chapter::where('series_id', $chapter->series_id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->where('published_at', '<=', now())
            ->orderBy('chapter_number', 'desc')
            ->first();

        $nextChapter = Chapter::where('series_id', $chapter->series_id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->where('published_at', '<=', now())
            ->orderBy('chapter_number', 'asc')
            ->first();

        // Increment view count
        $chapter->increment('views');

        return view('chapters.read', compact('chapter', 'isUnlocked', 'previousChapter', 'nextChapter'));
    }

    public function unlock(Request $request, $id)
    {
        try {
            $chapter = Chapter::findOrFail($id);
            $this->unlockService->unlock(auth()->user(), $chapter);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
