<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') — FlySeas Travels</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

{{-- Flash Messages --}}
@if(session('success'))
    <div style="position:fixed; top:20px; right:24px; z-index:200; background:#fff; border:1px solid var(--fs-line); border-left:4px solid var(--fs-primary); border-radius:var(--fs-r-md); padding:14px 20px; box-shadow:var(--fs-shadow-lg); max-width:380px; display:flex; align-items:flex-start; gap:12px;" id="flash-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--fs-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <div style="font-size:14px; color:var(--fs-ink);">{{ session('success') }}</div>
        <button onclick="document.getElementById('flash-success').style.display='none'" style="margin-left:auto; color:var(--fs-ink-soft); line-height:1; background:none; border:none; cursor:pointer;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <script>setTimeout(function(){ var el=document.getElementById('flash-success'); if(el) el.style.display='none'; }, 4000);</script>
@endif

@if(session('error'))
    <div style="position:fixed; top:20px; right:24px; z-index:200; background:#fff; border:1px solid var(--fs-line); border-left:4px solid #C0392B; border-radius:var(--fs-r-md); padding:14px 20px; box-shadow:var(--fs-shadow-lg); max-width:380px; display:flex; align-items:flex-start; gap:12px;" id="flash-error">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C0392B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div style="font-size:14px; color:var(--fs-ink);">{{ session('error') }}</div>
        <button onclick="document.getElementById('flash-error').style.display='none'" style="margin-left:auto; color:var(--fs-ink-soft); line-height:1; background:none; border:none; cursor:pointer;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <script>setTimeout(function(){ var el=document.getElementById('flash-error'); if(el) el.style.display='none'; }, 4000);</script>
@endif

<div class="admin-app">

    {{-- Sidebar --}}
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <a href="{{ route('admin.dashboard') }}" style="display:inline-flex; align-items:center; gap:8px;">
                <img src="{{ asset('images/logo.png') }}" alt="FlySeas Travels" style="height:34px; width:auto; display:block;">
                <span class="admin-brand-sub">Admin</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">Main</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.enquiries.index') }}"
               class="nav-item {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Enquiries
                @php $newCount = \App\Models\Enquiry::where('status','new')->count(); @endphp
                @if($newCount > 0)
                    <span class="badge">{{ $newCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.packages.index') }}"
               class="nav-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Itineraries
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">Content</div>

            <a href="#" class="nav-item" style="opacity:.5; pointer-events:none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                Destinations
                <span style="font-size:10px; margin-left:auto; color:var(--fs-ink-faint);">Soon</span>
            </a>

            <a href="#" class="nav-item" style="opacity:.5; pointer-events:none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Users
                <span style="font-size:10px; margin-left:auto; color:var(--fs-ink-faint);">Soon</span>
            </a>
        </div>

        <div class="nav-group" style="margin-top:auto; border-top:1px solid var(--fs-line-soft); padding-top:16px;">
            <a href="{{ route('home') }}" class="nav-item" target="_blank">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                View Public Site
            </a>
        </div>
    </aside>

    {{-- Right column: topbar + main --}}
    <div style="display:flex; flex-direction:column; min-height:100vh; overflow:hidden;">

        {{-- Topbar --}}
        <header class="admin-topbar">
            <div class="topbar-left">
                <div class="breadcrumb-admin">
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    @yield('breadcrumb')
                </div>
            </div>
            <div class="topbar-right">
                @auth
                    <div class="admin-user">
                        <div style="text-align:right; line-height:1.2;">
                            <div class="admin-user-name">{{ auth()->user()->name }}</div>
                            <div class="admin-user-email">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="avatar">{{ \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->take(2)->map(fn($p) => mb_substr($p,0,1))->implode('') }}</div>
                        <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="admin-logout" title="Sign out">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Sign out
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </header>

        {{-- Main content --}}
        <main class="admin-main">
            @yield('admin-content')
        </main>

    </div>

</div>

</body>
</html>
