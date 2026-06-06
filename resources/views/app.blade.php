<!DOCTYPE html>
@php
    $loc = app()->getLocale();
    $locales = config('app.locales');
    $dir = $locales[$loc]['dir'] ?? 'ltr';
    $fontClass = $dir === 'rtl' ? 'font-arabic' : 'font-sans';
@endphp
<html lang="{{ str_replace('_', '-', $loc) }}" dir="{{ $dir }}" class="{{ $fontClass }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#f1f5f9">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="description" content="{{ config('app.name') }} — Digital studio, products, and services.">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/favicon.svg">
    <title inertia>{{ config('app.name') }}</title>
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
    @unless (request()->routeIs('admin.*'))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endunless
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
