{{-- Package Card — MakeMyTrip-style clean layout
     Variables: $package (required), $ratio (optional: '16/10' default)
--}}
@php
    $ratio = $ratio ?? '16/10';
    $waText = urlencode("Hi! I'd like to know more about the {$package->title} package.");
    $iconLabels = ['flights' => 'Flights', 'hotel' => 'Hotels', 'meals' => 'Meals', 'transfers' => 'Transfers'];
@endphp

<a href="{{ route('packages.show', $package->slug) }}" class="pkg" style="text-decoration:none; color:inherit;">
    <div class="pkg-media" style="aspect-ratio: {{ $ratio }};">
        <img src="{{ $package->hero_image }}" alt="{{ $package->title }}" loading="lazy">

        @if($package->badge === 'bestseller')
            <span class="pkg-ribbon">Bestseller</span>
        @elseif($package->badge === 'new')
            <span class="pkg-ribbon new">New</span>
        @elseif($package->badge === 'limited')
            <span class="pkg-ribbon limited">
                @if($package->seats_left){{ $package->seats_left }} seats left @else Limited @endif
            </span>
        @endif

        <button class="pkg-save" type="button" aria-label="Save" onclick="event.preventDefault();">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>
    </div>

    <div class="pkg-body">
        <div class="pkg-rating-row">
            <span class="pkg-rating">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                {{ number_format($package->rating, 1) }}
            </span>
            <span class="pkg-rating-count">({{ $package->review_count }})</span>
            <span class="pkg-meta dot">·</span>
            <span class="pkg-loc">{{ $package->location }}</span>
        </div>

        <h3 class="pkg-title">{{ $package->title }}</h3>

        <div class="pkg-meta">
            <span>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                {{ $package->days }}D / {{ $package->nights }}N
            </span>
            <span class="dot">·</span>
            <span>{{ ucfirst($package->tier) }}</span>
            <span class="dot">·</span>
            <span>{{ ucfirst($package->category) }}</span>
        </div>

        @if($package->includes_icons && count($package->includes_icons))
            <div class="pkg-includes">
                @foreach($package->includes_icons as $icon)
                    <span class="pkg-inc">
                        @if($icon === 'flights')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
                        @elseif($icon === 'hotel')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/></svg>
                        @elseif($icon === 'meals')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                        @elseif($icon === 'transfers')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-3.6a1 1 0 0 0-.8-.4H5.24a2 2 0 0 0-1.8 1.1l-.8 1.63A6 6 0 0 0 2 12.42V16h2"/><circle cx="6.5" cy="16.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/></svg>
                        @endif
                        {{ $iconLabels[$icon] ?? ucfirst($icon) }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="pkg-price-row">
            <div>
                <div class="pkg-price-label">Starts from</div>
                <div class="pkg-price">
                    ₹{{ number_format($package->price_per_person) }}<span class="pkg-price-suffix">/person</span>
                </div>
            </div>
        </div>
    </div>

    <div class="pkg-actions">
        <span class="pkg-cta">
            View details
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
        <span class="pkg-wa" onclick="event.preventDefault(); event.stopPropagation(); window.open('https://wa.me/918421617391?text={{ $waText }}','_blank');" title="Enquire on WhatsApp">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
        </span>
    </div>
</a>
