<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-gray-700 text-sm font-bold">{{ __('Permissions') }}</label>
                                <div class="flex space-x-2">
                                    <button type="button" onclick="document.querySelectorAll('input[name=\'permissions[]\']').forEach(el => el.checked = true)" class="text-xs text-blue-600 hover:text-blue-800">{{ __('Select All') }}</button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" onclick="document.querySelectorAll('input[name=\'permissions[]\']').forEach(el => el.checked = false)" class="text-xs text-blue-600 hover:text-blue-800">{{ __('Deselect All') }}</button>
                                </div>
                            </div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 border p-4 rounded bg-gray-50 max-h-96 overflow-y-auto">
                                @foreach($permissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                                        <label for="perm_{{ $permission->id }}" class="ml-2 text-sm text-gray-700 select-none cursor-pointer">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Create Role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
