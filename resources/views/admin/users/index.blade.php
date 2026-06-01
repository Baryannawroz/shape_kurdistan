<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Create User') }}
                </a>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Roles') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            @foreach($user->roles as $role)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-teal-600 hover:text-teal-800 mr-2">{{ __('Edit') }}</a>
                                            
                                            @if(!$user->hasRole('super-admin') || \App\Models\User::role('super-admin')->count() > 1)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
