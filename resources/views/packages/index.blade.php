@extends('layouts.app')

@php
    $cat = request('category');
    $catLabel = $cat ? ' · ' . ucfirst($cat) : '';
    $catDesc  = $cat ? ucfirst($cat) . ' tour ' : 'Tour ';
@endphp

@section('title', 'All Tour Packages' . $catLabel . ' — FlySeas Travels')
@section('meta_description', $catDesc . 'packages from FlySeas Travels — Bali, Manali, Kerala, Dubai, Thailand and more. Filter by destination, duration and budget. Get a custom quote in 30 minutes.')
@section('meta_keywords', 'tour packages India, ' . ($cat ? "{$cat} tour packages, " : '') . 'holiday packages from Nagpur, FlySeas Travels, group tours, honeymoon packages, family holidays')

@section('content')

{{-- ===== PAGE HERO ===== --}}
<section class="page-hero">
    <div class="fs-container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span>All Tours</span>
        </div>
        <h1 class="fs-display fs-display-sm" style="margin-bottom:8px;">
            @if(request('q'))
                Results for "{{ request('q') }}"
            @elseif(request('category') && request('category') !== 'all')
                {{ ucfirst(request('category')) }} Packages
            @else
                All Tour Packages
            @endif
        </h1>
        <p style="color:var(--fs-ink-muted); font-size:15px;">
            {{ $packages->total() }} package{{ $packages->total() !== 1 ? 's' : '' }} found
            @if(request('category') && request('category') !== 'all')
                in <strong>{{ ucfirst(request('category')) }}</strong>
            @endif
        </p>
    </div>
</section>

{{-- ===== CHIP FILTER BAR ===== --}}
<div class="chip-bar">
    <div class="fs-container">
        <div class="chip-bar-inner">
            <a href="{{ route('packages.index') }}" class="fs-pill {{ !request('category') || request('category') === 'all' ? 'active' : '' }}">
                All Packages
            </a>
            <a href="{{ route('packages.index') }}?category=honeymoon{{ request('q') ? '&q='.urlencode(request('q')) : '' }}" class="fs-pill {{ request('category') === 'honeymoon' ? 'active' : '' }}">
                Honeymoon
            </a>
            <a href="{{ route('packages.index') }}?category=group{{ request('q') ? '&q='.urlencode(request('q')) : '' }}" class="fs-pill {{ request('category') === 'group' ? 'active' : '' }}">
                Group Tours
            </a>
            <a href="{{ route('packages.index') }}?category=family{{ request('q') ? '&q='.urlencode(request('q')) : '' }}" class="fs-pill {{ request('category') === 'family' ? 'active' : '' }}">
                Family
            </a>
            <a href="{{ route('packages.index') }}?category=adventure{{ request('q') ? '&q='.urlencode(request('q')) : '' }}" class="fs-pill {{ request('category') === 'adventure' ? 'active' : '' }}">
                Adventure
            </a>
            <a href="{{ route('packages.index') }}?category=international{{ request('q') ? '&q='.urlencode(request('q')) : '' }}" class="fs-pill {{ request('category') === 'international' ? 'active' : '' }}">
                International
            </a>
            <a href="{{ route('packages.index') }}?category=college{{ request('q') ? '&q='.urlencode(request('q')) : '' }}" class="fs-pill {{ request('category') === 'college' ? 'active' : '' }}">
                College Trips
            </a>
        </div>
    </div>
</div>

{{-- ===== LISTING LAYOUT ===== --}}
<div class="fs-container">
    <div class="listing-layout">

        {{-- ===== LEFT SIDEBAR FILTERS ===== --}}
        <aside>
            <form action="{{ route('packages.index') }}" method="GET" class="filters">
                <div class="filter-head">
                    <h3>Filters</h3>
                    <a href="{{ route('packages.index') }}" style="font-size:13px; color:var(--fs-primary); font-weight:500;">Clear all</a>
                </div>

                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                {{-- Destination --}}
                <div class="filter-group">
                    <div class="filter-label">Destination</div>
                    <div class="filter-options">
                        @foreach([
                            ['value' => 'Bali',         'label' => 'Bali, Indonesia'],
                            ['value' => 'Manali',       'label' => 'Manali, India'],
                            ['value' => 'Kerala',       'label' => 'Kerala, India'],
                            ['value' => 'Dubai',        'label' => 'Dubai, UAE'],
                            ['value' => 'Phuket & Krabi', 'label' => 'Thailand'],
                            ['value' => 'Goa',          'label' => 'Goa, India'],
                        ] as $dest)
                        <label class="check-row">
                            <span class="left">
                                <input type="checkbox" name="destination[]" value="{{ $dest['value'] }}"
                                    {{ in_array($dest['value'], (array) request('destination', [])) ? 'checked' : '' }}
                                    style="accent-color:var(--fs-primary);">
                                {{ $dest['label'] }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Trip Type --}}
                <div class="filter-group">
                    <div class="filter-label">Trip Type</div>
                    <div class="filter-options">
                        @foreach(['honeymoon' => 'Honeymoon', 'group' => 'Group', 'family' => 'Family', 'adventure' => 'Adventure', 'international' => 'International', 'college' => 'College'] as $val => $label)
                        <label class="check-row">
                            <span class="left">
                                <input type="checkbox" name="type[]" value="{{ $val }}"
                                    {{ in_array($val, (array) request('type', [])) ? 'checked' : '' }}
                                    style="accent-color:var(--fs-primary);">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Duration --}}
                <div class="filter-group">
                    <div class="filter-label">Duration</div>
                    <div class="filter-options">
                        @foreach(['1-3' => '1–3 days', '4-6' => '4–6 days', '7-10' => '7–10 days', '10+' => '10+ days'] as $val => $label)
                        <label class="check-row">
                            <span class="left">
                                <input type="checkbox" name="duration[]" value="{{ $val }}"
                                    {{ in_array($val, (array) request('duration', [])) ? 'checked' : '' }}
                                    style="accent-color:var(--fs-primary);">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Budget --}}
                <div class="filter-group">
                    <div class="filter-label">Budget (per person)</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:8px;">
                        <input type="number" name="budget_min" placeholder="Min ₹" class="form-input" value="{{ request('budget_min') }}" style="height:40px; font-size:13px;">
                        <input type="number" name="budget_max" placeholder="Max ₹" class="form-input" value="{{ request('budget_max') }}" style="height:40px; font-size:13px;">
                    </div>
                </div>

                {{-- Includes --}}
                <div class="filter-group">
                    <div class="filter-label">Includes</div>
                    <div class="filter-options">
                        @foreach(['flights' => 'Flights', 'hotel' => 'Hotel', 'meals' => 'Meals', 'transfers' => 'Transfers'] as $val => $label)
                        <label class="check-row">
                            <span class="left">
                                <input type="checkbox" name="includes[]" value="{{ $val }}"
                                    {{ in_array($val, (array) request('includes', [])) ? 'checked' : '' }}
                                    style="accent-color:var(--fs-primary);">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="fs-btn fs-btn-primary" style="width:100%; justify-content:center; margin-top:4px;">
                    Apply Filters
                </button>
            </form>
        </aside>

        {{-- ===== RIGHT: RESULTS ===== --}}
        <div>
            {{-- Results header --}}
            <div class="results-head">
                <div class="results-count">
                    Showing <strong>{{ $packages->firstItem() ?? 0 }}–{{ $packages->lastItem() ?? 0 }}</strong> of <strong>{{ $packages->total() }}</strong> packages
                </div>
                <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:var(--fs-ink-muted);">
                    <span>Sort by:</span>
                    <select onchange="window.location.href=this.value" style="border:1px solid var(--fs-line); border-radius:var(--fs-r-md); padding:6px 10px; font-size:13px; outline:none; cursor:pointer; background:#fff; color:var(--fs-ink);">
                        @php
                            $baseQuery = request()->except('sort');
                            $sortOptions = [
                                'popular'    => 'Most Popular',
                                'price_asc'  => 'Price: Low to High',
                                'price_desc' => 'Price: High to Low',
                                'rating'     => 'Top Rated',
                                'newest'     => 'Newest First',
                            ];
                        @endphp
                        @foreach($sortOptions as $val => $label)
                            <option value="{{ route('packages.index') }}?{{ http_build_query(array_merge($baseQuery, ['sort' => $val])) }}"
                                {{ request('sort', 'popular') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Package grid --}}
            @if($packages->count() > 0)
                <div class="card-grid">
                    @foreach($packages as $package)
                        @include('partials._pkg_card', ['package' => $package, 'ratio' => '16/10'])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div style="margin-top:48px;">
                    {{ $packages->links() }}
                </div>
            @else
                <div style="text-align:center; padding:80px 24px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--fs-ink-faint)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 16px;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <h3 style="font-size:20px; font-weight:600; color:var(--fs-ink); margin-bottom:8px;">No packages found</h3>
                    <p style="color:var(--fs-ink-muted); margin-bottom:24px;">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('packages.index') }}" class="fs-btn fs-btn-primary">Clear all filters</a>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
