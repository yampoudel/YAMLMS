<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 text-center">
                {{ $page_info['title'] }}
            </h1>
        </div>
    </x-slot>

    {{-- Main Content --}}

    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for='title' class="block text-sm font-medium text-gray-700 mb-1"> Course Title
                                </lablel>
                                <input type='text' name ='title' id='title' value="{{ old('title') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        @error('title')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror

                        <!-- Course Description -->
                        <div>
                            <label for='description' class="block text-sm font-medium text-gray-700 mb-1"> Course
                                Description
                                </lablel>
                                <textarea name = "description" id = "description" maxlength="500" value="{{ old('description') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        @error('description')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Add Course
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
