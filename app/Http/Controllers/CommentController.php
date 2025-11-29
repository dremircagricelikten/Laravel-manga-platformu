<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Series;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new comment
     */
    public function store(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|in:series,chapter',
            'commentable_id' => 'required|integer',
            'content' => 'required|string|max:2000',
            'gif_url' => 'nullable|url',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Get the commentable model
        $commentableType = $request->commentable_type === 'series' ? Series::class : Chapter::class;
        $commentable = $commentableType::findOrFail($request->commentable_id);

        // Create comment
        $comment = $commentable->comments()->create([
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'gif_url' => $request->gif_url,
            'is_approved' => true, // Auto-approve, admins can moderate later
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully!',
            'comment' => $comment->load('user'),
        ]);
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        // Only the comment owner can delete it
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully!',
        ]);
    }

    /**
     * Toggle comment approval (admin only)
     */
    public function toggleApproval(Comment $comment)
    {
        // Check if user is admin
        if (!Auth::user()->hasRole('Super Admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->update([
            'is_approved' => !$comment->is_approved,
        ]);

        return response()->json([
            'success' => true,
            'message' => $comment->is_approved ? 'Comment approved' : 'Comment hidden',
        ]);
    }
}
