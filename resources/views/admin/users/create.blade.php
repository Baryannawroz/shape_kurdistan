<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.users.store') }}" method="POST">
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
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Password') }}</label>
                            <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Roles') }}</label>
                            <div class="mt-2 space-y-2">
                                @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                        <label for="role_{{ $role->id }}" class="ml-2 text-gray-700">{{ $role->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Create User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
