<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Chapter;
use Symfony\Component\HttpFoundation\Response;

class CheckChapterAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $chapter = $request->route('chapter');

        if (!$chapter instanceof Chapter) {
            $chapter = Chapter::findOrFail($chapter);
        }

        $user = $request->user();

        // Check if chapter can be accessed
        if (!$chapter->canBeAccessedBy($user)) {
            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Please login to access this chapter.');
            }

            if ($chapter->isLocked()) {
                return redirect()->route('chapters.unlock', $chapter)
                    ->with('info', 'This chapter is locked. Unlock it to continue reading.');
            }

            return redirect()->route('series.show', $chapter->series)
                ->with('error', 'You do not have access to this chapter.');
        }

        // Increment views
        $chapter->incrementViews();

        return $next($request);
    }
}
