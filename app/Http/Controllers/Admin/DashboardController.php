<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Enquiry;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'packages'      => Package::count(),
            'enquiries'     => Enquiry::count(),
            'new_enquiries' => Enquiry::where('status', 'new')->count(),
            'featured'      => Package::where('is_featured', true)->count(),
        ];

        $recent_enquiries = Enquiry::with('package')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_enquiries'));
    }
}
