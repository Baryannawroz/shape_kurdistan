<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.locales.'.app()->getLocale().'.dir', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700|poppins:400,600|noto-naskh-arabic:400,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        html[lang="ckb"] body, html[lang="ar"] body { font-family: 'Noto Naskh Arabic', serif; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    
    <!-- Navigation -->
    <nav x-data="{ open: false }" class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="font-bold text-xl text-blue-600">
                            {{ config('app.name') }}
                        </a>
                    </div>
                    <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8 rtl:space-x-reverse">
                        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('Home') }}
                        </a>
                        <a href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            {{ __('Blog') }}
                        </a>
                        <!-- Sections Dropdown or List -->
                        @foreach(\App\Models\Section::where('is_active', true)->orderBy('sort_order')->get() as $navSection)
                             <a href="{{ route('section.show', ['locale' => app()->getLocale(), 'section' => $navSection->slug]) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                {{ $navSection->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                            <span>{{ config('app.locales.'.app()->getLocale().'.flag') }} {{ config('app.locales.'.app()->getLocale().'.name') }}</span>
                            <svg class="ml-2 h-5 w-5 rtl:mr-2 rtl:ml-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50 rtl:left-0 rtl:right-auto" style="display: none;">
                            @foreach(config('app.locales') as $localeCode => $properties)
                                @if($localeCode !== app()->getLocale())
                                    <a href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['locale' => $localeCode])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $properties['flag'] }} {{ $properties['name'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ config('app.name') }}</h3>
                    <p class="text-gray-400">{{ __('Building a better future together.') }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="text-gray-400 hover:text-white">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}" class="text-gray-400 hover:text-white">{{ __('Blog') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('Contact Us') }}</h3>
                    <p class="text-gray-400">info@example.com</p>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
            </div>
        </div>
    </footer>

    <script>
        AOS.init();
    </script>
</body>
</html>
