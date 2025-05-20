<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Video Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('videos.create') }}" class="inline-flex items-center px-6 py-3 bg-teal-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-teal-600 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Upload New Video') }}
                        </a>
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __("Your Uploaded Videos") }}</h3>

                    @if(isset($videos) && $videos->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($videos as $video)
                                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out group">
                                    <div class="relative">
                                        @if($video->embed_html)
                                            <div class="aspect-w-16 aspect-h-9 mb-3 rounded overflow-hidden">
                                                {!! $video->embed_html !!}
                                            </div>
                                        @elseif($video->vimeo_link)
                                            <div class="aspect-w-16 aspect-h-9 mb-3 rounded bg-gray-200 flex items-center justify-center">
                                                <a href="{{ $video->vimeo_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                                    Watch on Vimeo
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                </a>
                                            </div>
                                        @else
                                            <div class="aspect-w-16 aspect-h-9 mb-3 rounded bg-gray-100 flex items-center justify-center">
                                                <p class="text-sm text-gray-500">Video player not available.</p>
                                            </div>
                                        @endif
                                    </div>

                                    <h4 class="text-lg font-semibold text-gray-800 mb-1 truncate" title="{{ $video->title }}">{{ $video->title }}</h4>
                                    
                                    @if($video->description)
                                        <p class="text-sm text-gray-600 mb-2 h-10 overflow-hidden">{{ Str::limit($video->description, 75) }}</p>
                                    @else
                                        <p class="text-sm text-gray-400 mb-2 h-10 italic">No description provided.</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mb-3">Uploaded: {{ $video->created_at->format('M d, Y') }}</p>

                                    <div class="flex justify-end">
                                        <form method="POST" action="{{ route('videos.destroy', $video) }}" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold uppercase tracking-wider opacity-75 group-hover:opacity-100 transition-opacity duration-300">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Add pagination if needed: $videos->links() --}}
                        <div class="mt-8">
                            {{ $videos->links() }}
                        </div>
                    @else
                        <p>No videos uploaded yet. <a href="{{ route('videos.create') }}" class="text-indigo-600 hover:text-indigo-800">Upload your first video!</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
