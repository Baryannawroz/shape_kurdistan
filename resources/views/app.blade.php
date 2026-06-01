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
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#22c55e">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="description" content="{{ config('app.name') }} — Digital studio, products, and services.">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/favicon.svg">
    <title inertia>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="min-h-screen antialiased">
@inertia
</body>
</html>
