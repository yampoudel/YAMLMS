<x-app-layout>
    <style>
        /* This targets the editor's editable area specifically */
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>
    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <!-- SECTION 1: Extreme Left (The Button) -->
            <div class="flex-1 flex justify-start">
                <a href="{{ route('lessons.index') }}"
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
                <form action="{{ route('lessons.store') }}" method="POST">
                    @csrf

                    {{-- Metadata Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="col-span-1">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('title')
                                <span class="text-red-700 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Course Assign -->
                        <div class="col-span-1">
                            <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course
                                Assign</label>
                            <select name="course_id" id="course_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">-- Select a Course --</option>
                                @foreach ($courses as $course_option)
                                    <option value="{{ $course_option->id }}"
                                        {{ old('course_id') == $course_option->id || (isset($course) && $course->id == $course_option->id) ? 'selected' : '' }}>
                                        {{ $course_option->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <span class="text-red-700 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Lesson Status -->
                        <div class="col-span-1">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="Disabled" {{ old('status') == 'Disabled' ? 'selected' : '' }}>Disabled
                                </option>
                            </select>
                        </div>

                        <!-- Lesson Type -->
                        <div class="col-span-1">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" id="type"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="Default" {{ old('type') == 'Default' ? 'selected' : '' }}>Default
                                </option>
                                <option value="Survey" {{ old('type') == 'Survey' ? 'selected' : '' }}>Survey</option>
                                <option value="Quiz" {{ old('type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                            </select>
                        </div>

                        <!-- Description - FORCED FULL WIDTH -->
                        <div class="md:col-span-2">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    {{-- Content Area - FULL WIDTH --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Lesson
                            Content</label>
                        <textarea name="content" id="content" class="w-full">{{ old('content') }}</textarea>
                        @error('content')
                            <span class="text-red-700 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                            Add Lesson
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Check if the editor is loaded and the element exists
                const editorElement = document.querySelector('#content');

                if (editorElement && window.ClassicEditor) {
                    window.ClassicEditor
                        .create(editorElement)
                        .catch(error => {
                            console.error('CKEditor error:', error);
                        });
                }
            });
        </script>
    @endpush
</x-app-layout>
