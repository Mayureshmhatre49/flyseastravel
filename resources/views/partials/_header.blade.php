<header class="fs-header">
    <div class="fs-container">
        <div class="fs-header-inner">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="fs-logo" aria-label="FlySeas Travels — Home">
                <img src="{{ asset('images/logo.png') }}" alt="FlySeas Travels" height="44" style="height:44px; width:auto; display:block;">
            </a>

            {{-- Nav --}}
            <nav class="fs-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('packages.index') }}" class="{{ request()->routeIs('packages.index') || request()->routeIs('packages.show') ? 'active' : '' }}">Tours</a>
                <a href="{{ route('packages.index') }}?category=international">Destinations</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </nav>

            {{-- CTAs --}}
            <div class="fs-header-cta">
                <a href="tel:+918421617391" class="fs-btn fs-btn-ghost" style="display:none; display:inline-flex;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.08 6.08l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    +91 84216 17391
                </a>
                <a href="{{ route('packages.index') }}" class="fs-btn fs-btn-primary">
                    Plan my trip
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>
