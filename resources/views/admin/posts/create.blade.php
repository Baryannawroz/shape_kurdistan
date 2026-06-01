<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="col-span-2">
                                <div class="mb-4">
                                    <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Slug') }}</label>
                                    <input type="text" name="slug" id="slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                
                                <div x-data="{ lang: 'en' }" class="mb-4 border rounded p-4">
                                    <div class="flex border-b mb-4">
                                        @foreach(config('app.locales') as $locale => $details)
                                            <button type="button" @click="lang = '{{ $locale }}'" :class="{ 'border-b-2 border-blue-500 font-bold text-blue-600': lang === '{{ $locale }}', 'text-gray-500': lang !== '{{ $locale }}' }" class="mr-4 py-2 px-4 focus:outline-none">
                                                {{ $details['flag'] }} {{ $details['name'] }}
                                            </button>
                                        @endforeach
                                    </div>

                                    @foreach(config('app.locales') as $locale => $details)
                                        <div x-show="lang === '{{ $locale }}'" style="display: none;" :style="lang === '{{ $locale }}' ? 'display: block' : 'display: none'">
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Title') }} ({{ $locale }})</label>
                                                <input type="text" name="title[{{ $locale }}]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Excerpt') }} ({{ $locale }})</label>
                                                <textarea name="excerpt[{{ $locale }}]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Body') }} ({{ $locale }})</label>
                                                <textarea name="body[{{ $locale }}]" id="editor-{{ $locale }}" class="rich-editor w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                            </div>
                                            
                                            <div class="mt-6 border-t pt-4">
                                                <h4 class="font-bold mb-2 text-gray-600">{{ __('SEO') }} ({{ $locale }})</h4>
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Meta Title') }}</label>
                                                    <input type="text" name="meta_title[{{ $locale }}]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Meta Description') }}</label>
                                                    <textarea name="meta_description[{{ $locale }}]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-span-1 space-y-6">
                                <div class="bg-gray-50 p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-4">{{ __('Publishing') }}</h3>
                                    
                                    <div class="mb-4">
                                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status') }}</label>
                                        <select name="status" id="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="draft">{{ __('Draft') }}</option>
                                            <option value="published">{{ __('Published') }}</option>
                                            <option value="scheduled">{{ __('Scheduled') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="published_at" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Publish Date') }}</label>
                                        <input type="datetime-local" name="published_at" id="published_at" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>

                                    <div class="mb-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="allow_comments" value="1" checked class="form-checkbox h-5 w-5 text-blue-600">
                                            <span class="ml-2 text-gray-700">{{ __('Allow Comments') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-4">{{ __('Organization') }}</h3>
                                    
                                    <div class="mb-4">
                                        <label for="section_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Section') }}</label>
                                        <select name="section_id" id="section_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">{{ __('None') }}</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Category') }}</label>
                                        <select name="category_id" id="category_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">{{ __('None') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Tags') }}</label>
                                        <div class="max-h-32 overflow-y-auto border rounded p-2 bg-white">
                                            @foreach($tags as $tag)
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}" class="mr-2">
                                                    <label for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-4">{{ __('Featured Image') }}</h3>
                                    <input type="file" name="featured_image" id="featured_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end border-t pt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Create Post') }}
                            </button>
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
                            uploadUrl: '{{ route('admin.posts.editor-image') }}',
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
