<x-public-layout>
    <div class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-lg shadow-lg mb-8">
            @endif
            
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <span>{{ $post->created_at->format('F d, Y') }}</span>
                    @if($post->section)
                        <span class="mx-2">&bull;</span>
                        <a href="{{ route('section.show', ['locale' => app()->getLocale(), 'section' => $post->section->slug]) }}" class="text-blue-600 font-semibold hover:underline">{{ $post->section->name }}</a>
                    @endif
                    <span class="mx-2">&bull;</span>
                    <span>{{ __('By') }} {{ $post->author->name }}</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
            </div>
            
            <div class="prose prose-lg max-w-none mb-12">
                {!! $post->body !!}
            </div>
            
            <!-- Share Buttons (Mockup) -->
            <div class="border-t pt-8 mb-12">
                <h3 class="font-bold mb-4">{{ __('Share this post') }}</h3>
                <div class="flex space-x-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Facebook</button>
                    <button class="bg-blue-400 text-white px-4 py-2 rounded">Twitter</button>
                    <button class="bg-green-500 text-white px-4 py-2 rounded">WhatsApp</button>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
