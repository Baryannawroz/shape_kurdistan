<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#f1f5f9">
    <title inertia>{{ config('app.name') }} — CMS</title>
    <style>
        html { color-scheme: light; background-color: #f1f5f9; }
        body { background-color: #f1f5f9; color: #0f172a; }
        #app-loading-fallback {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 0.875rem;
            color: #475569;
        }
    </style>
    @include('partials.vite-admin-preloads')
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @inertiaHead
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <noscript>
        <div style="padding: 2rem; font-family: system-ui, sans-serif; text-align: center;">
            <p style="font-weight: 600; color: #0f172a;">JavaScript is required to use the admin panel.</p>
            <p style="margin-top: 0.5rem; color: #475569;">Enable JavaScript in your browser and reload this page.</p>
        </div>
    </noscript>
    <div id="app-loading-fallback" role="status" aria-live="polite">Loading…</div>
    @inertia
</body>
</html>
