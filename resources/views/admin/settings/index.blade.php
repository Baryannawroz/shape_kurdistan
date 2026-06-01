<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Site Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="text-lg font-bold mb-4">{{ __('General') }}</h3>
                                <div class="mb-4">
                                    <label for="site_name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Site Name') }}</label>
                                    <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label for="contact_email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Contact Email') }}</label>
                                    <input type="email" name="contact_email" id="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label for="site_logo" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Logo') }}</label>
                                    @if(isset($settings['site_logo']))
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-16 w-auto mb-2">
                                    @endif
                                    <input type="file" name="site_logo" id="site_logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                <div class="mb-4">
                                    <label for="site_favicon" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Favicon') }}</label>
                                    @if(isset($settings['site_favicon']))
                                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8 w-8 mb-2">
                                    @endif
                                    <input type="file" name="site_favicon" id="site_favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-bold mb-4">{{ __('Social Media') }}</h3>
                                <div class="mb-4">
                                    <label for="social_facebook" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Facebook URL') }}</label>
                                    <input type="url" name="social_facebook" id="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label for="social_twitter" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Twitter URL') }}</label>
                                    <input type="url" name="social_twitter" id="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label for="social_instagram" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Instagram URL') }}</label>
                                    <input type="url" name="social_instagram" id="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="mb-4">
                                    <label for="social_whatsapp" class="block text-gray-700 text-sm font-bold mb-2">{{ __('WhatsApp URL') }}</label>
                                    <input type="url" name="social_whatsapp" id="social_whatsapp" value="{{ $settings['social_whatsapp'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end border-t pt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Save Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
