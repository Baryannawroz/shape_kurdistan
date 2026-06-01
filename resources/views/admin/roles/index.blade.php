<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles & Permissions Matrix') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end space-x-2">
                <a href="{{ route('admin.permissions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Manage Permissions') }}
                </a>
                <a href="{{ route('admin.roles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Create Role') }}
                </a>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider sticky left-0 z-10 bg-gray-100 border-b border-r shadow-sm">
                                        {{ __('Role') }}
                                    </th>
                                    @foreach($permissions as $permission)
                                        <th class="px-2 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-b border-r min-w-[30px]" title="{{ $permission->name }}">
                                            <div class="transform -rotate-45 origin-bottom-left inline-block w-4 h-24 mb-2 align-bottom">
                                                {{ $permission->name }}
                                            </div>
                                        </th>
                                    @endforeach
                                    <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider sticky right-0 z-10 bg-gray-100 border-b border-l shadow-sm">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($roles as $role)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-bold sticky left-0 bg-white z-10 border-r shadow-sm">
                                            {{ $role->name }}
                                        </td>
                                        @foreach($permissions as $permission)
                                            <td class="px-2 py-4 whitespace-nowrap text-center border-r">
                                                @if($role->permissions->contains('id', $permission->id))
                                                    <span class="text-green-600 font-bold text-lg">✓</span>
                                                @else
                                                    <span class="text-gray-200">·</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium sticky right-0 bg-white z-10 border-l shadow-sm text-center">
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="text-teal-600 hover:text-teal-800 mr-2">{{ __('Edit') }}</a>
                                            
                                            @if($role->name !== 'super-admin')
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
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
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
