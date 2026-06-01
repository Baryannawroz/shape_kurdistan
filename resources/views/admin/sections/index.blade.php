<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sections') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ route('admin.sections.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Create Section') }}
                </a>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Slug') }}</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Active') }}</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($sections as $section)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        {{ $section->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        {{ $section->slug }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $section->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
                                        <a href="{{ route('admin.sections.edit', $section) }}" class="text-teal-600 hover:text-teal-800 mr-2">{{ __('Edit') }}</a>
                                        <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
