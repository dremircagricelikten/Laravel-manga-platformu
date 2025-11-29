<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Models\Series;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Toggle a reaction (add/remove/change)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|in:series,chapter',
            'reactionable_id' => 'required|integer',
            'type' => 'required|in:like,love,haha,wow,sad,angry',
        ]);

        // Get the reactionable model
        $reactionableType = $request->reactionable_type === 'series' ? Series::class : Chapter::class;
        $reactionable = $reactionableType::findOrFail($request->reactionable_id);

        // Check if user already reacted
        $existingReaction = $reactionable->reactions()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReaction) {
            // Same reaction - remove it
            if ($existingReaction->type === $request->type) {
                $existingReaction->delete();
                $action = 'removed';
            } else {
                // Different reaction - update it
                $existingReaction->update(['type' => $request->type]);
                $action = 'changed';
            }
        } else {
            // No reaction yet - create it
            $reactionable->reactions()->create([
                'user_id' => Auth::id(),
                'type' => $request->type,
            ]);
            $action = 'added';
        }

        // Get updated reactions summary
        $summary = $reactionable->getReactionsSummary();

        return response()->json([
            'success' => true,
            'action' => $action,
            'reactions' => $summary,
        ]);
    }

    /**
     * Get reactions for a specific item
     */
    public function index(Request $request)
    {
        $request->validate([
            'reactionable_type' => 'required|in:series,chapter',
            'reactionable_id' => 'required|integer',
        ]);

        $reactionableType = $request->reactionable_type === 'series' ? Series::class : Chapter::class;
        $reactionable = $reactionableType::findOrFail($request->reactionable_id);

        $summary = $reactionable->getReactionsSummary();
        
        // Get user's reaction if authenticated
        $userReaction = null;
        if (Auth::check()) {
            $userReaction = $reactionable->reactions()
                ->where('user_id', Auth::id())
                ->first()?->type;
        }

        return response()->json([
            'reactions' => $summary,
            'user_reaction' => $userReaction,
        ]);
    }
}
