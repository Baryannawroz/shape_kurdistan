<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Section') }}: {{ $section->name }}
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
            <form action="{{ route('admin.sections.update', $section) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                                <input type="text" name="name[{{ $locale }}]" value="{{ old('name.'.$locale, $section->getTranslation('name', $locale, false)) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>

                                            <!-- Subtitle -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subtitle') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                <input type="text" name="subtitle[{{ $locale }}]" value="{{ old('subtitle.'.$locale, $section->getTranslation('subtitle', $locale, false)) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>

                                            <!-- Description -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Short Description') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                <textarea name="description[{{ $locale }}]" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description.'.$locale, $section->getTranslation('description', $locale, false)) }}</textarea>
                                            </div>

                                            <!-- About Content (Rich Text) -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('About Us Content') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                <textarea name="about_content[{{ $locale }}]" id="editor-{{ $locale }}" class="rich-editor w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('about_content.'.$locale, $section->getTranslation('about_content', $locale, false)) }}</textarea>
                                            </div>

                                            <div class="border-t pt-4 mt-6">
                                                <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('SEO & Contact') }}</h4>
                                                
                                                <div class="grid grid-cols-1 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Contact Address') }} <span class="text-gray-400">({{ $locale }})</span></label>
                                                        <textarea name="contact_address[{{ $locale }}]" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('contact_address.'.$locale, $section->getTranslation('contact_address', $locale, false)) }}</textarea>
                                                    </div>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Meta Title') }}</label>
                                                            <input type="text" name="meta_title[{{ $locale }}]" value="{{ old('meta_title.'.$locale, $section->getTranslation('meta_title', $locale, false)) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Meta Description') }}</label>
                                                            <textarea name="meta_description[{{ $locale }}]" rows="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('meta_description.'.$locale, $section->getTranslation('meta_description', $locale, false)) }}</textarea>
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
                                        <input type="email" name="contact_email" id="contact_email" value="{{ $section->contact_email }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
                                        <input type="text" name="contact_phone" id="contact_phone" value="{{ $section->contact_phone }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="maps_embed_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Google Maps Embed URL') }}</label>
                                        <textarea name="maps_embed_url" id="maps_embed_url" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-xs">{{ $section->maps_embed_url }}</textarea>
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
                                            <input type="checkbox" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <span class="text-gray-700 font-medium">{{ __('Active') }}</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Slug') }}</label>
                                        <input type="text" name="slug" id="slug" value="{{ $section->slug }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div>
                                        <label for="color_theme" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Theme Color') }}</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="color_theme" id="color_theme" value="{{ $section->color_theme }}" class="h-10 w-10 border border-gray-300 rounded p-1 cursor-pointer">
                                            <span class="text-sm text-gray-500">{{ __('Select a brand color for this section') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                        {{ __('Update Section') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Media') }}</h3>
                                <div class="space-y-6">
                                    <!-- Cover Image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Cover Image') }}</label>
                                        @if($section->cover_image)
                                            <div class="mb-3 relative group">
                                                <img src="{{ asset('storage/' . $section->cover_image) }}" alt="Cover" class="w-full h-32 object-cover rounded-md border border-gray-200">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200"></div>
                                            </div>
                                        @endif
                                        <input type="file" name="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>

                                    <!-- Logo -->
                                    <div class="border-t pt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Logo') }}</label>
                                        @if($section->logo)
                                            <div class="mb-3 bg-gray-50 p-2 rounded-md border border-gray-200 inline-block">
                                                <img src="{{ asset('storage/' . $section->logo) }}" alt="Logo" class="h-12 w-auto">
                                            </div>
                                        @endif
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
