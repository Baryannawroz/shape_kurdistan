<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.permissions.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Permission Names') }}</label>
                            <p class="text-gray-600 text-xs mb-2">{{ __('Enter one permission per line or separate by commas. Example: create post, edit post, delete post') }}</p>
                            <textarea name="name" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="create post&#10;edit post&#10;delete post"></textarea>
                            @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                {{ __('Create Permissions') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
