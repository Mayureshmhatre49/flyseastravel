<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::with('package')->latest()->paginate(15);
        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,converted,closed',
        ]);

        $enquiry->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Enquiry status updated to "' . $request->status . '".');
    }
}
