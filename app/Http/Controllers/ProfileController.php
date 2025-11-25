<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Get user's bookmarked series (TODO: implement bookmarks table)
        $bookmarks = collect([]);

        // Get unlocked chapters
        $unlockedChapters = $user->unlockedChapters()
            ->with('chapter.series')
            ->latest()
            ->get();

        // Get transactions
        $transactions = $user->transactions()
            ->latest()
            ->paginate(20);

        return view('profile.index', compact('bookmarks', 'unlockedChapters', 'transactions'));
    }
}
