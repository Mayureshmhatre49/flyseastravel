<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::featured()->orderBy('sort_order')->take(3)->get();

        // Real destination counts derived from active packages
        $destinationCounts = Package::active()
            ->select('location', DB::raw('COUNT(*) as total'))
            ->groupBy('location')
            ->pluck('total', 'location');

        return view('home', [
            'packages'          => $packages,
            'destinationCounts' => $destinationCounts,
            'totalPackages'     => Package::active()->count(),
            'totalCountries'    => Package::active()->distinct('country')->count('country'),
        ]);
    }
}
