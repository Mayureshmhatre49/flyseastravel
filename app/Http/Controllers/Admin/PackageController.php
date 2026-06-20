<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $validated = $request->validate($this->rules(), $this->imageMessages());

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        // Convert newline-separated text fields to arrays
        $validated['highlights']  = $this->textToHighlights($request->input('highlights'));
        $validated['inclusions']  = $this->textToArray($request->input('inclusions'));
        $validated['exclusions']  = $this->textToArray($request->input('exclusions'));
        $validated['includes_icons'] = $this->textToArray($request->input('includes_icons'));

        // Resolve the ordered image set — first image is the hero, the rest are gallery.
        $images = $this->resolveImages($request);
        $validated['hero_image']     = $images[0] ?? null;
        $validated['gallery_images'] = array_slice($images, 1);

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
        $validated = $request->validate(
            $this->rules('slug,' . $package->id),
            $this->imageMessages()
        );

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        // Convert newline-separated text fields to arrays
        $validated['highlights']     = $this->textToHighlights($request->input('highlights'));
        $validated['inclusions']     = $this->textToArray($request->input('inclusions'));
        $validated['exclusions']     = $this->textToArray($request->input('exclusions'));
        $validated['includes_icons'] = $this->textToArray($request->input('includes_icons'));

        // Resolve the ordered image set — first image is the hero, the rest are gallery.
        $images = $this->resolveImages($request);
        $this->deleteRemovedImages($package, $images);
        $validated['hero_image']     = $images[0] ?? null;
        $validated['gallery_images'] = array_slice($images, 1);

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package "' . $validated['title'] . '" updated successfully.');
    }

    public function destroy(Package $package)
    {
        $title = $package->title;

        // Remove any uploaded image files belonging to this package.
        $this->deleteRemovedImages($package, []);

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package "' . $title . '" deleted.');
    }

    /**
     * Validation rules shared by store() and update().
     * Pass a unique-rule suffix (e.g. "slug,5") to ignore the current row on update.
     */
    private function rules(string $slugUnique = 'slug'): array
    {
        return [
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|unique:packages,' . $slugUnique,
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
            'is_featured'      => 'boolean',
            'is_active'        => 'boolean',
            // Image uploader: a JSON ordering string + the newly uploaded files.
            'image_order'      => 'nullable|string',
            'new_images'       => 'nullable|array',
            'new_images.*'     => 'image|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ];
    }

    /**
     * Friendly validation messages for the image uploads.
     */
    private function imageMessages(): array
    {
        return [
            'new_images.*.image' => 'Each uploaded file must be an image.',
            'new_images.*.mimes' => 'Images must be JPG, PNG, WEBP or GIF.',
            'new_images.*.max'   => 'Each image must be 4 MB or smaller.',
        ];
    }

    /**
     * Build the ordered list of image sources from the uploader.
     *
     * The form submits `image_order` — a JSON array of entries that are either
     * {type:"existing", value:"<url-or-path>"} for images already on the package,
     * or {type:"new", value:<index>} pointing into the uploaded `new_images` files.
     * Uploaded files are stored on the public disk and recorded as "/storage/..." paths.
     * The returned order is authoritative: index 0 is the hero image.
     */
    private function resolveImages(Request $request): array
    {
        $order = json_decode((string) $request->input('image_order', '[]'), true);
        if (! is_array($order)) {
            $order = [];
        }

        $newFiles = $request->file('new_images', []);
        if (! is_array($newFiles)) {
            $newFiles = [];
        }

        $images = [];
        foreach ($order as $entry) {
            $type  = is_array($entry) ? ($entry['type'] ?? null) : null;
            $value = is_array($entry) ? ($entry['value'] ?? null) : null;

            if ($type === 'existing') {
                $value = trim((string) $value);
                if ($value !== '') {
                    $images[] = $value;
                }
            } elseif ($type === 'new') {
                $file = $newFiles[(int) $value] ?? null;
                if ($file && $file->isValid()) {
                    $images[] = '/storage/' . $file->store('packages', 'public');
                }
            }
        }

        return array_values(array_unique($images));
    }

    /**
     * Delete locally-stored images that were removed from the package.
     * Externally hosted images (http/https URLs) are left untouched.
     */
    private function deleteRemovedImages(Package $package, array $keptImages): void
    {
        $existing = collect([$package->hero_image])
            ->merge($package->gallery_images ?? [])
            ->filter()
            ->filter(fn ($src) => Str::startsWith($src, '/storage/'));

        foreach ($existing->diff($keptImages) as $src) {
            $relative = Str::after($src, '/storage/');
            if ($relative !== '') {
                Storage::disk('public')->delete($relative);
            }
        }
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
