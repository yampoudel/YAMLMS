@push('scripts')
    @vite(['resources/js/course/course-validation.js'])
@endpush

<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <!-- SECTION 1: Extreme Left (The Button) -->
            <div class="flex-1 flex justify-start">
                <a href="{{ route('courses.index') }}"
                    class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                    <!-- SVG Icon makes the "Back" action clear -->
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ $page_info['back_button'] }}
                </a>
            </div>

            <!-- SECTION 2: Center (The Title) -->
            <div class="flex-1 text-center">
                <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                    {{ $page_info['title'] }}
                </h1>
            </div>

            <!-- SECTION 3: Right Spacer (Keeps the title perfectly in the middle) -->
            <div class="flex-1"></div>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form name="courseForm" action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for='title' class="block text-sm font-medium text-gray-700 mb-1"> Course Title
                                </lablel>
                                <input type='text' name ='title' id='title' value="{{ old('title') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <span class="js-title-error text-red-700 text-sm block mt-1">
                                    @error('title')
                                        {{ $message }}
                                    @enderror
                                </span>

                        </div>

                        <!-- Course Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Course Description
                            </label>
                            <textarea name="description" id="description" maxlength="500"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                            <span class="js-description-error text-red-700 text-sm block mt-1">
                                    @error('description')
                                        {{ $message }}
                                    @enderror
                            </span>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for='price' class="block text-sm font-medium text-gray-700 mb-1"> Course Price
                                </lablel>
                                <input type='number' name ='price' id='price' step='0.01', min='0' value="{{ old('price') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <span class="js-price-error text-red-700 text-sm block mt-1">
                                    @error('price')
                                        {{ $message }}
                                    @enderror
                                </span>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for='status' class="block text-sm font-medium text-gray-700 mb-1"> Course Status
                                </lablel>
                                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : ''}}>Active</option>
                                    <option value="Disabled" {{ old('status') == 'Disabled' ? 'selected' : ''}}>Disabled</option>
                                </select>
                                <span class="js-status-error text-red-700 text-sm block mt-1">
                                    @error('status')
                                        {{ $message }}
                                    @enderror
                                </span>
                        </div>

                        <!-- Course Image -->
                        <div>
                            <label for="course_image_path" class="block text-sm font-medium text-gray-700 mb-1">Course Image</label>
                            <input type="file" name="image_path" id='course_image_path' accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <span class="js-image-error text-red-700 text-sm block mt-1">
                                @error('image_path')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200" onclick="return validateCourse(this.form)">
                            Add Course
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
