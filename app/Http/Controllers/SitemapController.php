<?php

namespace App\Http\Controllers;

use App\Models\Package;

class SitemapController extends Controller
{
    public function index()
    {
        $packages = Package::active()->latest('updated_at')->get();

        $urls = [
            ['loc' => url('/'),                     'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('packages.index'),      'priority' => '0.9', 'changefreq' => 'daily'],
        ];

        foreach (['honeymoon', 'group', 'family', 'adventure', 'international', 'college'] as $cat) {
            $urls[] = [
                'loc'        => route('packages.index') . '?category=' . $cat,
                'priority'   => '0.7',
                'changefreq' => 'weekly',
            ];
        }

        foreach ($packages as $package) {
            $urls[] = [
                'loc'        => route('packages.show', $package->slug),
                'lastmod'    => optional($package->updated_at)->toAtomString(),
                'priority'   => $package->is_featured ? '0.9' : '0.8',
                'changefreq' => 'weekly',
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
