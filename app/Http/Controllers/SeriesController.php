<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function show($slug)
    {
        $series = Series::where('slug', $slug)
            ->with(['categories', 'chapters' => function($query) {
                $query->where('published_at', '<=', now())
                      ->orderBy('chapter_number', 'asc');
            }])
            ->withCount('chapters')
            ->firstOrFail();

        // Increment view count
        $series->increment('views');

        return view('series.show', compact('series'));
    }

    public function acceptNsfw(Request $request, $slug)
    {
        $series = Series::where('slug', $slug)->firstOrFail();
        
        // Store in session that user has accepted NSFW warning for this series
        session(['nsfw_accepted_' . $series->id => true]);
        
        return redirect()->route('series.show', $slug);
    }
}
