<x-public-layout>
    <div class="relative bg-gray-900 text-white" style="background-color: {{ $section->color_theme }}">
        @if($section->cover_image)
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $section->cover_image) }}" alt="{{ $section->name }}" class="w-full h-full object-cover opacity-50">
            </div>
        @endif
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $section->name }}</h1>
            <p class="text-xl max-w-2xl mx-auto">{{ $section->subtitle }}</p>
        </div>
    </div>

    <div class="bg-white border-b shadow-sm sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center space-x-8 rtl:space-x-reverse">
            <a href="{{ route('section.show', ['locale' => app()->getLocale(), 'section' => $section->slug]) }}" class="py-4 border-b-2 font-medium {{ request()->routeIs('section.show') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ __('Overview') }}
            </a>
            <a href="{{ route('section.about', ['locale' => app()->getLocale(), 'section' => $section->slug]) }}" class="py-4 border-b-2 font-medium {{ request()->routeIs('section.about') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ __('About Us') }}
            </a>
            <a href="{{ route('section.contact', ['locale' => app()->getLocale(), 'section' => $section->slug]) }}" class="py-4 border-b-2 font-medium {{ request()->routeIs('section.contact') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ __('Contact') }}
            </a>
        </div>
    </div>

    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose max-w-none">
                {!! \App\Support\RichContent::expand($section->about_content) !!}
            </div>
        </div>
    </div>
</x-public-layout>
