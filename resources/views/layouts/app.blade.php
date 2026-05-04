<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $defaultTitle = 'FlySeas Travels — Curated Holiday Packages from Nagpur';
        $defaultDesc  = 'Book honeymoon, group, family and adventure tour packages with FlySeas Travels. Curated itineraries from India to Bali, Dubai, Thailand, Kerala, Manali and more.';
        $defaultImage = asset('images/logo.png');

        $pageTitle    = trim($__env->yieldContent('title', $defaultTitle));
        $pageDesc     = trim($__env->yieldContent('meta_description', $defaultDesc));
        $pageKeywords = trim($__env->yieldContent('meta_keywords', 'travel agency Nagpur, holiday packages India, honeymoon tours, group tours, Bali tour, Manali tour, Kerala packages, Dubai packages, FlySeas Travels'));
        $pageImage    = trim($__env->yieldContent('og_image', $defaultImage));
        $pageUrl      = url()->current();
        $pageType     = trim($__env->yieldContent('og_type', 'website'));
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="author" content="{{ config('flyseas.company_name', 'FlySeas Travels') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $pageUrl }}">
    <meta name="theme-color" content="#FC6508">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $pageType }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:image" content="{!! e(html_entity_decode($pageImage)) !!}">
    <meta property="og:site_name" content="{{ config('flyseas.company_name', 'FlySeas Travels') }}">
    <meta property="og:locale" content="en_IN">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{!! e(html_entity_decode($pageImage)) !!}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Organization schema (site-wide) --}}
    @php
        $orgSchema = [
            '@context' => 'https://schema.org',
            '@type'    => 'TravelAgency',
            'name'     => config('flyseas.company_name', 'FlySeas Travels'),
            'description' => $defaultDesc,
            'url'      => config('flyseas.website', url('/')),
            'logo'     => asset('images/logo.png'),
            'image'    => asset('images/logo.png'),
            'telephone' => config('flyseas.phone', '+918421617391'),
            'email'    => config('flyseas.email', 'info@flyseastravels.com'),
            'address'  => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Nagpur',
                'addressRegion'   => 'Maharashtra',
                'addressCountry'  => 'IN',
            ],
            'foundingDate' => (string) config('flyseas.founded', 2018),
            'sameAs' => array_values(array_filter([
                config('flyseas.social.instagram'),
                config('flyseas.social.facebook'),
            ])),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @yield('head')
</head>
<body>

    {{-- Flash Message --}}
    @if(session('success'))
        <div style="position:fixed; top:80px; right:24px; z-index:200; background:#fff; border:1px solid var(--fs-line); border-left:4px solid var(--fs-primary); border-radius:var(--fs-r-md); padding:16px 20px; box-shadow:var(--fs-shadow-lg); max-width:380px; display:flex; align-items:flex-start; gap:12px;" id="flash-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <div>
                <div style="font-weight:600; font-size:14px; color:var(--fs-ink); margin-bottom:2px;">Enquiry Received!</div>
                <div style="font-size:13px; color:var(--fs-ink-muted);">{{ session('success') }}</div>
            </div>
            <button onclick="document.getElementById('flash-success').style.display='none'" style="margin-left:auto; color:var(--fs-ink-soft); line-height:1;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <script>
            setTimeout(function() {
                var el = document.getElementById('flash-success');
                if (el) el.style.display = 'none';
            }, 5000);
        </script>
    @endif

    {{-- Header --}}
    @include('partials._header')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials._footer')

    {{-- Floating WhatsApp Button --}}
    @include('partials._whatsapp_float')

    @yield('scripts')
</body>
</html>
