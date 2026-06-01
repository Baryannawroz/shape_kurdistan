<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>
            <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-150 ease-in-out">
                {{ __('Create New Post') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Section') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($posts as $post)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $post->title }}</div>
                                            <div class="text-sm leading-5 text-gray-500">{{ $post->author->name ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $post->section->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $post->category->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $post->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $post->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ optional($post->published_at)->format('Y-m-d') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-teal-600 hover:text-teal-800 mr-2">{{ __('Edit') }}</a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-no-wrap text-center text-gray-500">
                                            {{ __('No posts found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
