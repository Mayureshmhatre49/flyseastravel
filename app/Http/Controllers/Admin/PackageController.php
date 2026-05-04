<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderByDesc('created_at')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|unique:packages,slug',
            'location'         => 'required|string|max:255',
            'country'          => 'required|string|max:255',
            'category'         => 'required|in:honeymoon,group,family,adventure,international,college',
            'badge'            => 'required|in:bestseller,new,limited,none',
            'days'             => 'required|integer|min:1',
            'nights'           => 'required|integer|min:1',
            'tier'             => 'required|in:standard,premium',
            'price_per_person' => 'required|integer|min:0',
            'rating'           => 'required|numeric|min:0|max:5',
            'description'      => 'nullable|string',
            'overview'         => 'nullable|string',
            'hero_image'       => 'nullable|url',
            'is_featured'      => 'boolean',
            'is_active'        => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        // Convert newline-separated text fields to arrays
        $validated['highlights']  = $this->textToHighlights($request->input('highlights'));
        $validated['inclusions']  = $this->textToArray($request->input('inclusions'));
        $validated['exclusions']  = $this->textToArray($request->input('exclusions'));
        $validated['includes_icons'] = $this->textToArray($request->input('includes_icons'));

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package "' . $validated['title'] . '" created successfully.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|unique:packages,slug,' . $package->id,
            'location'         => 'required|string|max:255',
            'country'          => 'required|string|max:255',
            'category'         => 'required|in:honeymoon,group,family,adventure,international,college',
            'badge'            => 'required|in:bestseller,new,limited,none',
            'days'             => 'required|integer|min:1',
            'nights'           => 'required|integer|min:1',
            'tier'             => 'required|in:standard,premium',
            'price_per_person' => 'required|integer|min:0',
            'rating'           => 'required|numeric|min:0|max:5',
            'description'      => 'nullable|string',
            'overview'         => 'nullable|string',
            'hero_image'       => 'nullable|url',
            'is_featured'      => 'boolean',
            'is_active'        => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        // Convert newline-separated text fields to arrays
        $validated['highlights']     = $this->textToArray($request->input('highlights'));
        $validated['inclusions']     = $this->textToArray($request->input('inclusions'));
        $validated['exclusions']     = $this->textToArray($request->input('exclusions'));
        $validated['includes_icons'] = $this->textToArray($request->input('includes_icons'));

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package "' . $validated['title'] . '" updated successfully.');
    }

    public function destroy(Package $package)
    {
        $title = $package->title;
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package "' . $title . '" deleted.');
    }

    /**
     * Convert newline-separated string to array, filtering blank lines.
     */
    private function textToArray(?string $text): array
    {
        if (empty($text)) {
            return [];
        }
        return array_values(array_filter(
            array_map('trim', explode("\n", $text)),
            fn($line) => $line !== ''
        ));
    }

    /**
     * Parse highlights text where each line is "Title | Description".
     * Falls back to {title: line} if no pipe is present.
     */
    private function textToHighlights(?string $text): array
    {
        $lines = $this->textToArray($text);

        return array_map(function ($line) {
            if (str_contains($line, '|')) {
                [$title, $description] = array_map('trim', explode('|', $line, 2));
                return ['title' => $title, 'description' => $description];
            }
            return ['title' => $line, 'description' => ''];
        }, $lines);
    }
}
