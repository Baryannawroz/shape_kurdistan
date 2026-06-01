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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <div>
                    <h2 class="text-2xl font-bold mb-6">{{ __('Get in Touch') }}</h2>
                    <p class="mb-4 text-gray-600">{{ __('We would love to hear from you. Please fill out the form or contact us using the information below.') }}</p>
                    
                    @if($section->contact_email)
                        <div class="mb-4">
                            <strong class="block text-gray-800">{{ __('Email') }}</strong>
                            <a href="mailto:{{ $section->contact_email }}" class="text-blue-600">{{ $section->contact_email }}</a>
                        </div>
                    @endif
                    
                    @if($section->contact_phone)
                        <div class="mb-4">
                            <strong class="block text-gray-800">{{ __('Phone') }}</strong>
                            <a href="tel:{{ $section->contact_phone }}" class="text-blue-600">{{ $section->contact_phone }}</a>
                        </div>
                    @endif
                    
                    @if($section->contact_address)
                        <div class="mb-4">
                            <strong class="block text-gray-800">{{ __('Address') }}</strong>
                            <p class="text-gray-600">{{ $section->contact_address }}</p>
                        </div>
                    @endif

                    @if($section->maps_embed_url)
                        <div class="mt-8 h-64 bg-gray-200 rounded overflow-hidden">
                             <iframe src="{{ $section->maps_embed_url }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-50 p-8 rounded-lg">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    <form action="{{ route('contact.store', ['locale' => app()->getLocale(), 'section' => $section->slug]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Message') }}</label>
                            <textarea name="message" id="message" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300">
                                {{ __('Send Message') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
