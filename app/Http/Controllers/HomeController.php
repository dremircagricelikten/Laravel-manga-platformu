<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Chapter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured series for hero slider (5 latest featured)
        $featuredSeries = Series::where('is_featured', true)
            ->with('categories')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Trending series (most viewed in last 7 days)
        $trendingSeries = Series::withCount('chapters')
            ->orderBy('views', 'desc')
            ->take(12)
            ->get();

        // Latest chapters
        $latestChapters = Chapter::with('series')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(12)
            ->get();

        // Popular series
        $popularSeries = Series::withCount('chapters')
            ->orderBy('views', 'desc')
            ->take(8)
            ->get();

        return view('home', compact(
            'featuredSeries',
            'trendingSeries',
            'latestChapters',
            'popularSeries'
        ));
    }
}
