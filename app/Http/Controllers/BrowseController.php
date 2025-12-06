<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Category;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Series::with('categories')->withCount('chapters');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('alternative_titles', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->filled('categories')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'trending':
                $query->orderBy('views', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default: // latest
                $query->latest();
        }

        $series = $query->paginate(24)->appends($request->all());
        
        if ($request->wantsJson()) {
            return response()->json($series);
        }

        $categories = Category::orderBy('name')->get();

        return view('browse.index', compact('series', 'categories'));
    }
}
