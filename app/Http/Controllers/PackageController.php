<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::active();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('destination')) {
            $destinations = (array) $request->destination;
            $query->whereIn('location', $destinations);
        }

        if ($request->filled('type')) {
            $types = (array) $request->type;
            $query->whereIn('category', $types);
        }

        if ($request->filled('duration')) {
            $durations = (array) $request->duration;
            $query->where(function ($q) use ($durations) {
                foreach ($durations as $dur) {
                    if ($dur === '1-3') {
                        $q->orWhereBetween('days', [1, 3]);
                    } elseif ($dur === '4-6') {
                        $q->orWhereBetween('days', [4, 6]);
                    } elseif ($dur === '7-10') {
                        $q->orWhereBetween('days', [7, 10]);
                    } elseif ($dur === '10+') {
                        $q->orWhere('days', '>', 10);
                    }
                }
            });
        }

        if ($request->filled('budget_min')) {
            $query->where('price_per_person', '>=', $request->budget_min);
        }
        if ($request->filled('budget_max')) {
            $query->where('price_per_person', '<=', $request->budget_max);
        }

        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_per_person', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_person', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('sort_order')->orderBy('is_featured', 'desc');
                break;
        }

        $packages = $query->paginate(12)->withQueryString();

        return view('packages.index', compact('packages'));
    }

    public function show(string $slug)
    {
        $package = Package::where('slug', $slug)
            ->where('is_active', true)
            ->with('packageDays')
            ->firstOrFail();

        return view('packages.show', compact('package'));
    }
}
