<x-public-layout>
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-12 text-center">{{ __('Latest News & Updates') }}</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition duration-300">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                @if($post->section)
                                    <span class="mx-2">&bull;</span>
                                    <span class="text-blue-600 font-semibold">{{ $post->section->name }}</span>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold mb-2"><a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'post' => $post->slug]) }}" class="hover:text-blue-600">{{ $post->title }}</a></h2>
                            <p class="text-gray-600 line-clamp-3">{{ $post->excerpt }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-public-layout>
