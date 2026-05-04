<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'nullable|email|max:255',
            'destination'  => 'nullable|string|max:255',
            'travel_dates' => 'nullable|string|max:255',
            'message'      => 'nullable|string|max:2000',
            'package_id'   => 'nullable|exists:packages,id',
        ]);

        Enquiry::create($validated);

        return redirect()->back()->with('success', 'Thank you! We have received your enquiry and will get back to you shortly.');
    }
}
