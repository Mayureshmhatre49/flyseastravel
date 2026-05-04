<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — FlySeas Travels</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="login-shell">

    <aside class="login-aside">
        <div class="login-brand">
            <span style="display:inline-flex; padding:8px 12px; background:#fff; border-radius:var(--fs-r-md);">
                <img src="{{ asset('images/logo.png') }}" alt="FlySeas Travels" style="height:44px; width:auto; display:block;">
            </span>
        </div>

        <div>
            <h2>Crafting extraordinary journeys, one trip at a time.</h2>
            <p>Welcome to the FlySeas Travels admin console. Manage itineraries, track enquiries, and keep every traveller's dream on track.</p>
        </div>

        <div class="login-foot">
            &copy; {{ date('Y') }} FlySeas Travels · Nagpur, India
        </div>
    </aside>

    <main class="login-main">
        <div class="login-card">

            <h1>Admin Sign In</h1>
            <p class="login-sub">Enter your credentials to manage tours and enquiries.</p>

            @if ($errors->any())
                <div class="login-error">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="login-error" style="background: var(--fs-primary-light); color: var(--fs-primary); border-color: var(--fs-primary-light);">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('admin.login.attempt') }}" method="POST" class="login-form">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="field-input"
                        placeholder="admin@flyseastravels.com"
                        autocomplete="email"
                        autofocus
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="field-input"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div class="row-between">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="login-cta">
                    Sign in
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </form>

            @if(app()->environment('local'))
                <div class="login-hint">
                    <strong>Local dev credentials:</strong><br>
                    Email: <code>admin@flyseastravels.com</code><br>
                    Password: <code>flyseas2026</code>
                </div>
            @endif

            <a href="{{ route('home') }}" class="login-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Back to FlySeas
            </a>
        </div>
    </main>

</div>

</body>
</html>
