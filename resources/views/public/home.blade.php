<x-public-layout>
    <!-- Hero -->
    <div class="bg-blue-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ __('Welcome to Our Organization') }}</h1>
            <p class="text-xl mb-8">{{ __('Leading the way in innovation and service.') }}</p>
            <a href="#sections" class="bg-white text-blue-900 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition duration-300">{{ __('Explore Sections') }}</a>
        </div>
    </div>

    <!-- Sections Overview -->
    <div id="sections" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">{{ __('Our Sections') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($sections as $section)
                    <div class="bg-gray-50 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        @if($section->cover_image)
                            <img src="{{ asset('storage/' . $section->cover_image) }}" alt="{{ $section->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">{{ __('No Image') }}</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2" style="color: {{ $section->color_theme }}">{{ $section->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $section->subtitle }}</p>
                            <a href="{{ route('section.show', ['locale' => app()->getLocale(), 'section' => $section->slug]) }}" class="text-blue-600 font-semibold hover:text-blue-800 flex items-center">
                                {{ __('Explore') }} 
                                <svg class="w-4 h-4 ml-2 rtl:mr-2 rtl:ml-0 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Latest Posts -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">{{ __('Latest News') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latestPosts as $post)
                    <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-md transition duration-300" data-aos="fade-up">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <div class="text-sm text-gray-500 mb-2">{{ $post->created_at->format('M d, Y') }}</div>
                            <h3 class="text-xl font-bold mb-2"><a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'post' => $post->slug]) }}" class="hover:text-blue-600">{{ $post->title }}</a></h3>
                            <p class="text-gray-600 line-clamp-3">{{ $post->excerpt }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}" class="inline-block border border-blue-600 text-blue-600 font-bold py-2 px-6 rounded hover:bg-blue-600 hover:text-white transition duration-300">
                    {{ __('View All News') }}
                </a>
            </div>
        </div>
    </div>
</x-public-layout>
