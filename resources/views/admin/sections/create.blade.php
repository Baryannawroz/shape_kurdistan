<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Section') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.sections.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Content & Translations -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Content Card -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Section Content') }}</h3>
                                
                                <div x-data="{ lang: '{{ app()->getLocale() }}' }">
                                    <!-- Language Tabs -->
                                    <div class="flex border-b border-gray-200 mb-6 overflow-x-auto">
                                        @foreach(config('app.locales') as $locale => $details)
                                            <button type="button" 
                                                    @click="lang = '{{ $locale }}'" 
                                                    :class="{ 'border-blue-500 text-blue-600': lang === '{{ $locale }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': lang !== '{{ $locale }}' }" 
                                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8 transition-colors duration-200">
                                                <span class="mr-2">{{ $details['flag'] }}</span> {{ $details['name'] }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Language Panels -->
                                    @foreach(config('app.locales') as $locale => $details)
                                        <div x-show="lang === '{{ $locale }}'" style="display: none;" :style="lang === '{{ $locale }}' ? 'display: block' : 'display: none'" class="space-y-4">
                                            
                                            <!-- Name -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                <input type="text" name="name[{{ $locale }}]" value="{{ old('name.'.$locale) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('e.g., Technology') }}">
                                            </div>

                                            <!-- Subtitle -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subtitle') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                <input type="text" name="subtitle[{{ $locale }}]" value="{{ old('subtitle.'.$locale) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="{{ __('e.g., Innovation and Future') }}">
                                            </div>

                                            <!-- Description -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Short Description') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                <textarea name="description[{{ $locale }}]" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description.'.$locale) }}</textarea>
                                            </div>

                                            <!-- About Content (Rich Text) -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('About Us Content') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                <textarea name="about_content[{{ $locale }}]" id="editor-{{ $locale }}" class="rich-editor w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('about_content.'.$locale) }}</textarea>
                                            </div>

                                            <div class="border-t pt-4 mt-6">
                                                <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('SEO & Contact') }}</h4>
                                                
                                                <div class="grid grid-cols-1 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contact Address') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                        <textarea name="contact_address[{{ $locale }}]" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('contact_address.'.$locale) }}</textarea>
                                                    </div>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Meta Title') }}</label>
                                                            <input type="text" name="meta_title[{{ $locale }}]" value="{{ old('meta_title.'.$locale) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Meta Description') }}</label>
                                                            <textarea name="meta_description[{{ $locale }}]" rows="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('meta_description.'.$locale) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Contact Details (Shared) -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Contact Information') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                                        <input type="email" name="contact_email" id="contact_email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
                                        <input type="text" name="contact_phone" id="contact_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="maps_embed_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Google Maps Embed URL') }}</label>
                                        <textarea name="maps_embed_url" id="maps_embed_url" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-xs"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Settings & Media -->
                    <div class="space-y-6">
                        <!-- Publish Settings -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Publish Settings') }}</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="flex items-center space-x-3">
                                            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <span class="text-gray-700 font-medium">{{ __('Active') }}</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Slug') }}</label>
                                        <input type="text" name="slug" id="slug" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div>
                                        <label for="color_theme" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Theme Color') }}</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="color_theme" id="color_theme" value="#3B82F6" class="h-10 w-10 border border-gray-300 rounded p-1 cursor-pointer">
                                            <span class="text-sm text-gray-500">{{ __('Select a brand color for this section') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                        {{ __('Create Section') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Media') }}</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Cover Image') }}</label>
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors duration-200">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <label for="cover_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                        <span>Upload a file</span>
                                                        <input id="cover_image" name="cover_image" type="file" class="sr-only">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Logo') }}</label>
                                        <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach(config('app.locales') as $locale => $details)
                ClassicEditor
                    .create(document.querySelector('#editor-{{ $locale }}'), {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'imageUpload', 'insertTable', 'blockQuote', '|', 'undo', 'redo'],
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                                ]
                        },
                        simpleUpload: {
                            uploadUrl: '{{ route('admin.sections.editor-image') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            @endforeach
        });
    </script>
    @endpush
</x-admin-layout>
