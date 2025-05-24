<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload New Video
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Your form goes here -->
        <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
            @csrf

            <div class="mb-4">
                <label for="title" class="block font-medium text-sm text-gray-700">Title *</label>
                <input type="text" name="title" id="title" required
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                       value="{{ old('title') }}">
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                <textarea name="description" id="description"
                          class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="video_file" class="block font-medium text-sm text-gray-700">Main Video File *</label>
                <input type="file" name="video_file" id="video_file" required class="mt-1 block w-full">
            </div>

            <div class="mb-4">
                <label for="trailer" class="block font-medium text-sm text-gray-700">Trailer (Optional)</label>
                <input type="file" name="trailer" id="trailer" class="mt-1 block w-full">
            </div>

            <div class="mb-4">
                <label for="thumbnail" class="block font-medium text-sm text-gray-700">Thumbnail Image (Optional)</label>
                <input type="file" name="thumbnail" id="thumbnail" class="mt-1 block w-full">
            </div>

            <div class="mb-4">
                <label for="cast" class="block font-medium text-sm text-gray-700">Cast (Optional)</label>
                <input type="text" name="cast" id="cast" class="mt-1 block w-full" value="{{ old('cast') }}">
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Upload Video
            </button>
        </form>
    </div>
</x-app-layout>
