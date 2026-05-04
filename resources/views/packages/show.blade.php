@extends('layouts.app')

@section('title',           $package->meta_title)
@section('meta_description', $package->meta_description)
@section('meta_keywords',    $package->meta_keywords)
@section('og_image',         $package->hero_image ?: asset('images/logo.png'))
@section('og_type',          'product')

@section('head')
    @php
        $images = array_values(array_filter(array_merge(
            [$package->hero_image],
            $package->gallery_images ?? []
        )));

        $productSchema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $package->title,
            'description' => $package->description ?: $package->overview,
            'image'       => $images,
            'sku'         => 'PKG-' . $package->id,
            'brand'       => ['@type' => 'Brand', 'name' => 'FlySeas Travels'],
            'offers'      => [
                '@type'         => 'Offer',
                'url'           => url()->current(),
                'priceCurrency' => 'INR',
                'price'         => $package->price_per_person,
                'availability'  => $package->is_active ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller'        => ['@type' => 'TravelAgency', 'name' => 'FlySeas Travels'],
            ],
        ];
        if ($package->review_count > 0) {
            $productSchema['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => (float) $package->rating,
                'reviewCount' => $package->review_count,
                'bestRating'  => 5,
                'worstRating' => 1,
            ];
        }

        $itineraryItems = $package->packageDays->map(function ($d, $i) {
            return [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'item'     => [
                    '@type'       => 'TouristAttraction',
                    'name'        => "Day {$d->day_number}: {$d->title}",
                    'description' => $d->description,
                ],
            ];
        })->values()->all();

        $tripSchema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'TouristTrip',
            'name'            => $package->title,
            'description'     => $package->description ?: $package->overview,
            'image'           => $package->hero_image,
            'tourBookingPage' => url()->current(),
            'touristType'     => ucfirst($package->category),
            'itinerary'       => [
                '@type'           => 'ItemList',
                'itemListElement' => $itineraryItems,
            ],
            'offers' => [
                '@type'         => 'Offer',
                'priceCurrency' => 'INR',
                'price'         => $package->price_per_person,
            ],
        ];

        $breadcrumbSchema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tours', 'item' => route('packages.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $package->title, 'item' => url()->current()],
            ],
        ];

        $jsonOpts = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    @endphp
    <script type="application/ld+json">{!! json_encode($productSchema,    $jsonOpts) !!}</script>
    <script type="application/ld+json">{!! json_encode($tripSchema,       $jsonOpts) !!}</script>
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, $jsonOpts) !!}</script>
@endsection

@section('content')

@php
    $waText = urlencode("Hi! I'm interested in the {$package->title} package. Please share pricing and availability.");
    $galleryImages = $package->gallery_images ?? [];
@endphp

{{-- ===== BREADCRUMB ===== --}}
<div style="background:var(--fs-primary-tint); padding:20px 0; border-bottom:1px solid var(--fs-line);">
    <div class="fs-container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <a href="{{ route('packages.index') }}">Tours</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span>{{ $package->title }}</span>
        </div>
    </div>
</div>

<div class="fs-container" style="padding-top:32px;">

    {{-- ===== GALLERY GRID ===== --}}
    <div class="gallery">
        <div class="gallery-main">
            <img src="{{ $package->hero_image }}" alt="{{ $package->title }}">
        </div>
        @if(isset($galleryImages[0]))
        <div>
            <img src="{{ $galleryImages[0] }}" alt="{{ $package->title }} gallery 1">
        </div>
        @endif
        @if(isset($galleryImages[1]))
        <div>
            <img src="{{ $galleryImages[1] }}" alt="{{ $package->title }} gallery 2">
        </div>
        @endif
        @if(isset($galleryImages[2]))
        <div>
            <img src="{{ $galleryImages[2] }}" alt="{{ $package->title }} gallery 3">
        </div>
        @endif
        @if(isset($galleryImages[3]))
        <div class="gallery-cta">
            <img src="{{ $galleryImages[3] }}" alt="{{ $package->title }} gallery 4">
            @if(count($galleryImages) > 4)
            <button class="more-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                +{{ count($galleryImages) - 4 }} photos
            </button>
            @endif
        </div>
        @endif
    </div>

    {{-- ===== TITLE ROW ===== --}}
    <div class="title-row">
        <div style="flex:1;">
            <div class="title-loc">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $package->location }}, {{ $package->country }}
            </div>
            <h1>{{ $package->title }}</h1>
            <div class="title-meta">
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    {{ $package->days }} Days / {{ $package->nights }} Nights
                </span>
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ ucfirst($package->tier) }} Package
                </span>
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="var(--fs-accent-dark)" stroke-width="1"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    {{ number_format($package->rating, 1) }} ({{ $package->review_count }} reviews)
                </span>
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    {{ ucfirst($package->category) }}
                </span>
                @if($package->badge !== 'none')
                <span style="background:{{ $package->badge === 'bestseller' ? 'var(--fs-accent-light)' : ($package->badge === 'new' ? 'var(--fs-primary-light)' : '#FDE8E8') }}; color:{{ $package->badge === 'bestseller' ? 'var(--fs-accent-dark)' : ($package->badge === 'new' ? 'var(--fs-primary)' : '#C0392B') }}; padding:4px 12px; border-radius:var(--fs-r-pill); font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.04em;">
                    {{ ucfirst($package->badge) }}
                    @if($package->badge === 'limited' && $package->seats_left) — {{ $package->seats_left }} seats left @endif
                </span>
                @endif
            </div>
        </div>
        <div class="title-actions">
            <button class="icon-btn" title="Save to wishlist">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </button>
            <button class="icon-btn" title="Share" onclick="navigator.share ? navigator.share({title: '{{ $package->title }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
            <button class="icon-btn" title="Download PDF" onclick="window.print()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </button>
        </div>
    </div>

    {{-- ===== TAB NAV ===== --}}
    <div class="tabs" id="tabs">
        <a href="#overview" class="active" data-tab="overview">Overview</a>
        <a href="#itinerary" data-tab="itinerary">Day-by-Day</a>
        <a href="#inclusions" data-tab="inclusions">Inclusions</a>
        <a href="#policies" data-tab="policies">Policies</a>
        <a href="#reviews" data-tab="reviews">Reviews</a>
    </div>

    {{-- ===== 2-COL DETAIL LAYOUT ===== --}}
    <div class="detail-layout">

        {{-- ===== LEFT: MAIN CONTENT ===== --}}
        <div>

            {{-- Overview --}}
            <section class="section" id="overview">
                <h2>Overview</h2>
                <p class="lead">{{ $package->overview ?? $package->description }}</p>

                @if($package->highlights && count($package->highlights) > 0)
                    <div class="highlights">
                        @foreach($package->highlights as $highlight)
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                            </div>
                            <div>
                                <h4>{{ $highlight['title'] }}</h4>
                                <p>{{ $highlight['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Day-by-day itinerary --}}
            @if($package->packageDays->count() > 0)
            <section class="section" id="itinerary">
                <h2>Day-by-Day Itinerary</h2>
                <div class="timeline">
                    @foreach($package->packageDays as $index => $day)
                    <div class="day {{ $index === 0 ? 'open' : '' }}" id="day-{{ $day->day_number }}">
                        <div class="day-marker">{{ $day->day_number }}</div>
                        <div class="day-card">
                            <div class="day-head" onclick="toggleDay(this)">
                                <div>
                                    <h3>Day {{ $day->day_number }}: {{ $day->title }}</h3>
                                    <div class="day-loc">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $day->location }}
                                    </div>
                                </div>
                                <div class="day-toggle">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </div>
                            </div>
                            <div class="day-body">
                                <p>{{ $day->description }}</p>
                                @if($day->activities && count($day->activities) > 0)
                                <div class="day-activities">
                                    @foreach($day->activities as $activity)
                                    <div class="day-activity">
                                        <span class="time">{{ $activity['time'] }}</span>
                                        <span class="text">{{ $activity['text'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Inclusions / Exclusions --}}
            <section class="section" id="inclusions">
                <h2>Inclusions &amp; Exclusions</h2>
                <div class="inc-exc">
                    <div class="inc-card included">
                        <h3>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            What's Included
                        </h3>
                        <ul>
                            @foreach($package->inclusions ?? [] as $item)
                            <li>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="inc-card excluded">
                        <h3>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Not Included
                        </h3>
                        <ul>
                            @foreach($package->exclusions ?? [] as $item)
                            <li>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>

            {{-- Policies --}}
            <section class="section" id="policies">
                <h2>Policies</h2>
                <div style="background:var(--fs-bg-soft); border:1px solid var(--fs-line); border-radius:var(--fs-r-lg); padding:28px;">
                    <div style="display:grid; gap:20px;">
                        <div>
                            <h4 style="font-size:14px; font-weight:600; color:var(--fs-ink); margin-bottom:6px; display:flex; gap:8px; align-items:center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                Cancellation Policy
                            </h4>
                            <p style="font-size:13px; color:var(--fs-ink-muted); line-height:1.6;">30+ days before departure: Full refund. 15–29 days: 50% refund. 7–14 days: 25% refund. Less than 7 days: No refund. All cancellations must be submitted in writing.</p>
                        </div>
                        <div>
                            <h4 style="font-size:14px; font-weight:600; color:var(--fs-ink); margin-bottom:6px; display:flex; gap:8px; align-items:center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                Payment Policy
                            </h4>
                            <p style="font-size:13px; color:var(--fs-ink-muted); line-height:1.6;">25% advance at the time of booking. Remaining 75% to be paid 15 days before departure. We accept UPI, bank transfer, and all major credit/debit cards.</p>
                        </div>
                        <div>
                            <h4 style="font-size:14px; font-weight:600; color:var(--fs-ink); margin-bottom:6px; display:flex; gap:8px; align-items:center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Travel Insurance
                            </h4>
                            <p style="font-size:13px; color:var(--fs-ink-muted); line-height:1.6;">We strongly recommend purchasing travel insurance. We can assist with arranging comprehensive coverage that includes trip cancellation, medical emergencies, and baggage loss.</p>
                        </div>
                        <div>
                            <h4 style="font-size:14px; font-weight:600; color:var(--fs-ink); margin-bottom:6px; display:flex; gap:8px; align-items:center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                Important Notes
                            </h4>
                            <p style="font-size:13px; color:var(--fs-ink-muted); line-height:1.6;">Prices are per person on twin-sharing basis. Single occupancy available at an additional cost. Itinerary is subject to change due to weather, force majeure, or local conditions. FlySeas Travels reserves the right to modify the programme for safety.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Reviews (static for MVP) --}}
            <section class="section" id="reviews">
                <h2>Guest Reviews</h2>
                <div style="display:flex; gap:24px; align-items:center; padding:24px; background:var(--fs-primary-tint); border-radius:var(--fs-r-lg); margin-bottom:24px;">
                    <div style="text-align:center; flex-shrink:0;">
                        <div style="font-family:var(--fs-display); font-size:56px; font-weight:500; color:var(--fs-primary); line-height:1;">{{ number_format($package->rating, 1) }}</div>
                        <div style="display:flex; gap:2px; justify-content:center; margin:6px 0;">
                            @for($i = 1; $i <= 5; $i++)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= round($package->rating) ? 'var(--fs-accent)' : 'none' }}" stroke="var(--fs-accent-dark)" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <div style="font-size:13px; color:var(--fs-ink-muted);">{{ $package->review_count }} reviews</div>
                    </div>
                    <div style="flex:1;">
                        @foreach([5 => 78, 4 => 15, 3 => 5, 2 => 1, 1 => 1] as $star => $pct)
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px; font-size:12px; color:var(--fs-ink-muted);">
                            <span style="min-width:8px;">{{ $star }}</span>
                            <div style="flex:1; height:6px; background:var(--fs-line); border-radius:3px; overflow:hidden;">
                                <div style="height:100%; width:{{ $pct }}%; background:var(--fs-accent); border-radius:3px;"></div>
                            </div>
                            <span style="min-width:28px;">{{ $pct }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Sample reviews --}}
                <div style="display:flex; flex-direction:column; gap:20px;">
                    @foreach([
                        ['name' => 'Priya & Rohan Sharma', 'loc' => 'Mumbai', 'rating' => 5, 'text' => 'Absolutely magical experience! Every detail was taken care of. The private villa was stunning and the Jimbaran dinner was the highlight of our honeymoon. FlySeas made our dream come true.', 'date' => '2 weeks ago'],
                        ['name' => 'Arjun Mehta', 'loc' => 'Bangalore', 'rating' => 5, 'text' => 'Seamless planning, great hotel, and the tour guide was incredibly knowledgeable. I\'ve traveled with 3 different agencies and FlySeas is by far the best. Already planning my next trip with them!', 'date' => '1 month ago'],
                        ['name' => 'Sneha Patel', 'loc' => 'Pune', 'rating' => 4, 'text' => 'Loved the itinerary and the team was very responsive on WhatsApp. Minor hiccup with hotel check-in but it was resolved quickly. Overall a fantastic trip — will recommend to friends!', 'date' => '2 months ago'],
                    ] as $review)
                    <div style="padding:20px; border:1px solid var(--fs-line); border-radius:var(--fs-r-lg);">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                            <div style="display:flex; gap:12px; align-items:center;">
                                <div style="width:40px; height:40px; border-radius:50%; background:var(--fs-primary-light); color:var(--fs-primary); display:flex; align-items:center; justify-content:center; font-weight:600; font-size:15px; flex-shrink:0;">
                                    {{ strtoupper(substr($review['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; font-size:14px;">{{ $review['name'] }}</div>
                                    <div style="font-size:12px; color:var(--fs-ink-soft);">{{ $review['loc'] }}</div>
                                </div>
                            </div>
                            <div style="font-size:12px; color:var(--fs-ink-soft);">{{ $review['date'] }}</div>
                        </div>
                        <div style="display:flex; gap:2px; margin-bottom:10px;">
                            @for($i = 1; $i <= 5; $i++)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= $review['rating'] ? 'var(--fs-accent)' : 'none' }}" stroke="var(--fs-accent-dark)" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <p style="font-size:14px; color:var(--fs-ink-muted); line-height:1.6;">{{ $review['text'] }}</p>
                    </div>
                    @endforeach
                </div>
            </section>

        </div>{{-- end left col --}}

        {{-- ===== RIGHT: STICKY SIDEBAR ===== --}}
        <aside class="sidebar">
            <div class="price-card">
                <div class="price-head">
                    <div class="price-from">Starting from</div>
                    <div>
                        <span class="price-amount">₹{{ number_format($package->price_per_person) }}</span>
                        <span class="price-suffix"> / person</span>
                    </div>
                    <div class="price-note">{{ $package->days }}D / {{ $package->nights }}N · {{ ucfirst($package->tier) }} · Twin sharing</div>
                    @if($package->badge === 'limited' && $package->seats_left)
                        <div style="margin-top:10px; background:#FDE8E8; color:#C0392B; font-size:12px; font-weight:600; padding:8px 12px; border-radius:var(--fs-r-md); display:flex; align-items:center; gap:6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Only {{ $package->seats_left }} seats remaining!
                        </div>
                    @endif
                </div>

                {{-- Quick enquiry form --}}
                <form action="{{ route('enquiry.store') }}" method="POST" class="quick-form">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <input type="hidden" name="destination" value="{{ $package->location }}, {{ $package->country }}">
                    <input type="text" name="name" class="form-input" placeholder="Your name" required value="{{ old('name') }}" style="height:44px;">
                    <input type="tel" name="phone" class="form-input" placeholder="Phone number" required value="{{ old('phone') }}" style="height:44px;">
                    <input type="text" name="travel_dates" class="form-input" placeholder="Preferred travel dates" value="{{ old('travel_dates') }}" style="height:44px;">
                    <textarea name="message" class="form-input" placeholder="Any special requirements?" rows="3">{{ old('message') }}</textarea>
                </form>

                <div class="submit-row">
                    <button type="submit" form="sidebar-form" class="btn-primary-full" onclick="this.closest('.price-card').querySelector('form').requestSubmit()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Send Enquiry
                    </button>
                    <a href="https://wa.me/918421617391?text={{ $waText }}" class="btn-wa-full" target="_blank" rel="noopener noreferrer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                        Chat on WhatsApp
                    </a>
                    <a href="tel:+918421617391" class="btn-call">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.08 6.08l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Call +91 84216 17391
                    </a>
                </div>

                <div class="trust-mini">
                    <div class="trust-mini-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Safe & secure booking
                    </div>
                    <div class="trust-mini-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        No hidden fees, transparent pricing
                    </div>
                    <div class="trust-mini-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.08 6.08l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        24/7 support during your trip
                    </div>
                    <div class="trust-mini-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        12,000+ happy travellers
                    </div>
                </div>
            </div>

            {{-- Related / Quick nav --}}
            <div style="margin-top:16px; background:#fff; border:1px solid var(--fs-line); border-radius:var(--fs-r-lg); padding:20px;">
                <h4 style="font-size:14px; font-weight:600; margin-bottom:12px;">Quick navigation</h4>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach(['overview' => 'Overview', 'itinerary' => 'Day-by-Day Itinerary', 'inclusions' => 'Inclusions & Exclusions', 'policies' => 'Policies', 'reviews' => 'Reviews'] as $id => $label)
                    <a href="#{{ $id }}" style="font-size:13px; color:var(--fs-ink-muted); display:flex; align-items:center; gap:8px; padding:6px 0; border-bottom:1px solid var(--fs-line-soft);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

    </div>{{-- end detail-layout --}}

</div>{{-- end fs-container --}}

@endsection

@section('scripts')
<script>
    // Day toggle functionality
    function toggleDay(head) {
        const day = head.closest('.day');
        day.classList.toggle('open');
    }

    // Tab smooth scroll
    document.querySelectorAll('.tabs a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            document.querySelectorAll('.tabs a').forEach(function(l) { l.classList.remove('active'); });
            this.classList.add('active');
        });
    });

    // Active tab on scroll
    const sections = document.querySelectorAll('.section');
    const tabLinks = document.querySelectorAll('.tabs a');
    window.addEventListener('scroll', function() {
        let current = '';
        sections.forEach(function(section) {
            const sectionTop = section.getBoundingClientRect().top;
            if (sectionTop <= 150) {
                current = section.id;
            }
        });
        tabLinks.forEach(function(link) {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
</script>
@endsection
