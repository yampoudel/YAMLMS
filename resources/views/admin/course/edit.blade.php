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
            <div class="flex-1">
                {{-- Passes the $course_id to the Lesson Creator --}}
                <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                    {{ $page_info['lesson_link'] }}
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- Success Message --}}
                @if (session('success'))
                    <div id='notify_course_updated' class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for='title' class="block text-sm font-medium text-gray-700 mb-1"> Course Title
                                </lablel>
                                <input type='text' name ='title' id='title'
                                    value="{{ old('title', $course->title) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                @error('title')
                                    <span class="text-red-700">{{ $message }}</span>
                                @enderror
                        </div>

                        <!-- Course Description -->
                        <div>
                            <label for='description' class="block text-sm font-medium text-gray-700 mb-1"> Course
                                Description
                            </label>
                            <textarea name = "description" id = "description" maxlength="500"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('description', $course->description) }}</textarea>

                            @error('description')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Course Image -->
                        <div>
                            <label for="course_image_path" class="block text-sm font-medium text-gray-700 mb-1">Course Image</label>
                            <div class="mb-3 flex items-center space-x-4">
                                <div class="relative">
                                    <img id="course-preview-display" src="{{ $course->course_image_url }}" alt="Current Course Image" 
                                        class="w-16 h-16 rounded-full object-cover border border-gray-300 shadow-sm">
                                </div>
                                <div class="text-xs text-gray-500">
                                    <p>Current uploaded file.</p>
                                    <p>Select a new file below to overwrite it.</p>
                                </div>
                            </div>

                            <!-- Upload Input Field -->
                            <input type="file" name="image_path" id="course_image_path" accept="image/*" onchange="handleImagePreview(this)"
                                class="block w-full text-sm text-gray-500 mt-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('image_path')
                                <span class="text-red-700 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                            Update Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                const el = document.getElementById('notify_course_updated');
                if (el) {
                    el.style.transition = "opacity 0.8s ease";
                    el.style.opacity = "0";

                    setTimeout(() => {
                        el.remove();
                    }, 800); // match transition duration
                }
            }, 3000); // wait 5 seconds before fading

            function handleImagePreview(inputField) {
            if (inputField.files && inputField.files[0]) {
                const fileReader = new FileReader();
                
                fileReader.onload = function(event) {
                    document.getElementById('course-preview-display').src = event.target.result;
                };
                
                fileReader.readAsDataURL(inputField.files[0]);
            }
        }
        </script>
    @endpush
</x-app-layout>
