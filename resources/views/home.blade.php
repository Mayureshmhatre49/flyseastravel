@extends('layouts.app')

@section('title', 'FlySeas Travels — Holiday Packages from Nagpur')
@section('meta_description', 'Book curated holiday packages from Nagpur with FlySeas Travels. Honeymoon, group, family and adventure itineraries to Bali, Manali, Kerala, Dubai and Thailand.')
@section('meta_keywords', 'holiday packages Nagpur, travel agency Nagpur, honeymoon packages, group tour packages, Bali tour from India, Manali tour from Nagpur, Kerala backwaters package, Dubai luxury package, FlySeas Travels')
@section('og_image', 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1200&q=85')

@section('head')
    @php
        $siteSchema = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => 'FlySeas Travels',
            'url'      => url('/'),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => url('/tours') . '?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($siteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero">

    {{-- Floating destination chips (decorative) --}}
    <div class="hero-floater fl-1" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22V12a10 10 0 1 1 20 0v10"/><path d="m6 16 6-4 6 4"/></svg>
        Bali · Indonesia
    </div>
    <div class="hero-floater fl-2" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2 12 7 6 12 12 17 8 22 14"/><polyline points="2 18 7 12 12 18 17 14 22 20"/></svg>
        Manali · Himachal
    </div>
    <div class="hero-floater fl-3" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h20M12 2a15 15 0 0 1 4 10 15 15 0 0 1-4 10 15 15 0 0 1-4-10 15 15 0 0 1 4-10z"/></svg>
        Dubai · UAE
    </div>
    <div class="hero-floater fl-4" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
        Kerala · Backwaters
    </div>

    <div class="fs-container">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="live-dot"></span>
                Crafting custom journeys since {{ config('flyseas.founded', 2018) }}
            </div>

            <h1>Holiday packages,<br><em>handcrafted for you.</em></h1>

            <p class="lead">From Nagpur to Bali — curated itineraries with hotels, transfers and sightseeing sorted.</p>

            {{-- Search with glow --}}
            <form action="{{ route('packages.index') }}" method="GET" style="width:100%; max-width:720px;">
                <div class="search-card-wrap">
                    <div class="search-card">
                        <div class="search-field">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div style="flex:1;">
                                <label for="hero-search">Where to</label>
                                <input type="text" id="hero-search" name="q" placeholder="Bali, Manali, Kerala…" value="{{ request('q') }}">
                            </div>
                        </div>
                        <div class="search-field">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div style="flex:1;">
                                <label for="hero-category">Trip type</label>
                                <select id="hero-category" name="category">
                                    <option value="">Any</option>
                                    <option value="honeymoon">Honeymoon</option>
                                    <option value="family">Family</option>
                                    <option value="adventure">Adventure</option>
                                    <option value="group">Group</option>
                                    <option value="college">College</option>
                                    <option value="international">International</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="search-submit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            Search
                        </button>
                    </div>
                </div>
            </form>

            <div class="quick-chips">
                <a href="{{ route('packages.index') }}?category=honeymoon" class="quick-chip">Honeymoon</a>
                <a href="{{ route('packages.index') }}?category=group" class="quick-chip">Group tours</a>
                <a href="{{ route('packages.index') }}?category=family" class="quick-chip">Family</a>
                <a href="{{ route('packages.index') }}?category=adventure" class="quick-chip">Adventure</a>
                <a href="{{ route('packages.index') }}?category=international" class="quick-chip">International</a>
                <a href="{{ route('packages.index') }}?category=college" class="quick-chip">College</a>
            </div>

            {{-- Trust strip --}}
            <div class="hero-trust">
                <div style="display:inline-flex; align-items:center; gap:6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#34d399;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <strong>Hand-crafted itineraries</strong>
                </div>
                <div class="div"></div>
                <div style="display:inline-flex; align-items:center; gap:6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <strong>Reply within 30 mins</strong>
                </div>
                <div class="div"></div>
                <div style="display:inline-flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color:var(--fs-accent);"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <strong>Personalised support</strong>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== STATS STRIP ===== --}}
<section class="stats-strip">
    <div class="fs-container">
        <div class="stats-grid">
            <div class="stat"><div class="stat-num">{{ $totalPackages }}</div><div class="stat-label">Curated itineraries</div></div>
            <div class="stat"><div class="stat-num">{{ $destinationCounts->count() }}</div><div class="stat-label">Destinations</div></div>
            <div class="stat"><div class="stat-num">{{ $totalCountries }}</div><div class="stat-label">Countries</div></div>
            <div class="stat"><div class="stat-num">{{ now()->year - config('flyseas.founded', 2018) }}+ yrs</div><div class="stat-label">In business</div></div>
        </div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
<section class="fs-section-sm">
    <div class="fs-container">
        <div class="section-header">
            <h2>Browse by trip type</h2>
            <p>Pick a vibe — we'll match you with the right itinerary.</p>
        </div>

        <div class="category-grid">
            <a href="{{ route('packages.index') }}?category=honeymoon" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
                <h3>Honeymoon</h3><p>For two</p>
            </a>
            <a href="{{ route('packages.index') }}?category=group" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <h3>Group tours</h3><p>10+ people</p>
            </a>
            <a href="{{ route('packages.index') }}?category=family" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
                <h3>Family</h3><p>With kids</p>
            </a>
            <a href="{{ route('packages.index') }}?category=adventure" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg></div>
                <h3>Adventure</h3><p>Treks & thrills</p>
            </a>
            <a href="{{ route('packages.index') }}?category=international" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div>
                <h3>International</h3><p>Beyond India</p>
            </a>
            <a href="{{ route('packages.index') }}?category=college" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 10-1.7 1.7a2 2 0 0 1-2.6 0L2 4l9-2 11 8z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
                <h3>College</h3><p>Student trips</p>
            </a>
            <a href="{{ route('packages.index') }}" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg></div>
                <h3>Weekend</h3><p>Quick getaways</p>
            </a>
            <a href="{{ route('packages.index') }}" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22V12a10 10 0 1 1 20 0v10"/><circle cx="7" cy="14" r="1"/><circle cx="17" cy="14" r="1"/></svg></div>
                <h3>Pilgrimage</h3><p>Spiritual trips</p>
            </a>
            <a href="{{ route('packages.index') }}" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h2m16 0h2M5.6 5.6l1.4 1.4M17 17l1.4 1.4M12 2v2m0 16v2"/><circle cx="12" cy="12" r="5"/></svg></div>
                <h3>Beach</h3><p>Sun & sand</p>
            </a>
            <a href="{{ route('packages.index') }}" class="category-tile">
                <div class="category-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="2 12 7 6 12 12 17 8 22 14"/><polyline points="2 18 7 12 12 18 17 14 22 20"/></svg></div>
                <h3>Hill stations</h3><p>Mountain escapes</p>
            </a>
        </div>
    </div>
</section>

{{-- ===== FEATURED PACKAGES ===== --}}
<section class="fs-section packages">
    <div class="fs-container">
        <div class="section-header">
            <h2>Top selling packages</h2>
            <p>Most-booked itineraries this season — handpicked by our travel experts.</p>
        </div>

        <div class="package-grid">
            @foreach($packages as $package)
                @include('partials._pkg_card', ['package' => $package, 'ratio' => '16/10'])
            @endforeach
        </div>

        <div style="text-align:center; margin-top:32px;">
            <a href="{{ route('packages.index') }}" class="fs-btn fs-btn-outline">
                View all packages
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ===== TOP DESTINATIONS ===== --}}
@php
    $bali    = $destinationCounts['Bali']           ?? 0;
    $manali  = ($destinationCounts['Manali']         ?? 0) + ($destinationCounts['Manali & Kasol'] ?? 0);
    $kerala  = $destinationCounts['Kerala']          ?? 0;
    $count   = fn ($n) => $n === 0 ? 'Coming soon' : ($n === 1 ? '1 itinerary' : "{$n} itineraries");
@endphp
<section class="fs-section">
    <div class="fs-container">
        <div class="section-header">
            <h2>Top destinations</h2>
            <p>Where our travellers are heading.</p>
        </div>

        <div class="dest-grid">
            <a href="{{ route('packages.index') }}?q=Bali" class="dest-card" rel="nofollow">
                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1200&q=80" alt="Bali honeymoon and beach holidays" loading="lazy">
                <div class="dest-overlay"><div class="dest-name">Bali</div><div class="dest-count">{{ $count($bali) }}</div></div>
            </a>
            <a href="{{ route('packages.index') }}?q=Manali" class="dest-card" rel="nofollow">
                <img src="https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=900&q=80" alt="Manali tour packages from Nagpur" loading="lazy">
                <div class="dest-overlay"><div class="dest-name">Manali</div><div class="dest-count">{{ $count($manali) }}</div></div>
            </a>
            <a href="{{ route('packages.index') }}?q=Kerala" class="dest-card" rel="nofollow">
                <img src="https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?w=900&q=80" alt="Kerala backwaters and houseboat tours" loading="lazy">
                <div class="dest-overlay"><div class="dest-name">Kerala</div><div class="dest-count">{{ $count($kerala) }}</div></div>
            </a>
            <a href="{{ route('packages.index') }}?q=Goa" class="dest-card" rel="nofollow">
                <img src="https://images.unsplash.com/photo-1587922546307-776227941871?w=900&q=80" alt="Goa beach holidays" loading="lazy">
                <div class="dest-overlay"><div class="dest-name">Goa</div><div class="dest-count">Coming soon</div></div>
            </a>
            <a href="{{ route('packages.index') }}?q=Dubai" class="dest-card" rel="nofollow">
                <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=900&q=80" alt="Dubai luxury holiday packages" loading="lazy">
                <div class="dest-overlay"><div class="dest-name">Dubai</div><div class="dest-count">Coming soon</div></div>
            </a>
        </div>
    </div>
</section>

{{-- ===== TRUST STRIP ===== --}}
<section class="fs-section-sm trust">
    <div class="fs-container">
        <div class="trust-grid">
            <div class="trust-item">
                <div class="trust-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <div><h4>Verified itineraries</h4><p>Every package vetted. No surprises.</p></div>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.08 6.08l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
                <div><h4>24/7 support</h4><p>WhatsApp us anytime — real humans.</p></div>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div><h4>Quick reply</h4><p>Hear back in 30 minutes.</p></div>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div><h4>Best price</h4><p>Find it cheaper — we'll match it.</p></div>
            </div>
        </div>
    </div>
</section>

{{-- ===== ENQUIRY ===== --}}
<section class="fs-section enquiry-band" id="contact">

    {{-- Decorative travel icons --}}
    <svg class="enquiry-decor tl" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
    </svg>
    <svg class="enquiry-decor tr" width="90" height="90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="12" cy="12" r="10"/>
        <path d="M2 12h20"/>
        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
    </svg>
    <svg class="enquiry-decor bl" width="84" height="84" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M14 22V8a4 4 0 0 0-8 0v14"/>
        <path d="M14 6c0-1.5-2-3-4-3-2.5 0-4 1-5 3"/>
        <path d="M14 6c2 1 3.5 3 4 5"/>
        <path d="M14 6c0-2.5 2-4 4.5-4 .8 0 1.5.2 2 .5"/>
        <path d="M10 22h4"/>
    </svg>
    <svg class="enquiry-decor br" width="86" height="86" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M2 12h20"/>
        <path d="M12 2v20"/>
        <circle cx="12" cy="12" r="9"/>
        <path d="m9 9 6 6m0-6-6 6"/>
    </svg>

    <div class="fs-container">
        <div class="enquiry-card">
            <h2 class="fs-display fs-display-sm" style="margin-bottom:10px;">Plan your trip with us</h2>
            <p style="color:var(--fs-ink-muted); font-size:15px; line-height:1.6; max-width:480px; margin:0 auto;">
                Tell us where you'd like to go — we'll send a custom itinerary in 24 hours. Free, no commitment.
            </p>

            <div class="enquiry-perks">
                <div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    No booking fee
                </div>
                <div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Reply in 30 mins
                </div>
                <div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Free changes
                </div>
            </div>

            <form action="{{ route('enquiry.store') }}" method="POST" class="enquiry-form">
                @csrf
                <div class="form-row">
                    <input type="text" name="name" class="form-input" placeholder="Your name" required value="{{ old('name') }}">
                    <input type="tel" name="phone" class="form-input" placeholder="Phone / WhatsApp" required value="{{ old('phone') }}">
                </div>
                <div class="form-row">
                    <input type="text" name="destination" class="form-input" placeholder="Destination" value="{{ old('destination') }}">
                    <input type="text" name="travel_dates" class="form-input" placeholder="Travel dates" value="{{ old('travel_dates') }}">
                </div>
                <textarea name="message" class="form-input" placeholder="Tell us about your trip — number of people, budget, preferences…" rows="3">{{ old('message') }}</textarea>
                <button type="submit" class="fs-btn fs-btn-primary" style="width:100%; justify-content:center; height:48px; font-size:14px; font-weight:600;">
                    Send enquiry
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </form>
        </div>
    </div>
</section>

@endsection
