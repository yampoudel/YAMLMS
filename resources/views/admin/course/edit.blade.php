<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 text-center">
                Edit User
            </h1>
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
                        </div>
                        @error('title')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror

                        <!-- Course Description -->
                        <div>
                            <label for='description' class="block text-sm font-medium text-gray-700 mb-1"> Course
                                Description
                            </label>
                            <textarea name = "description" id = "description" maxlength="500"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">{{ old('description', $course->description) }}</textarea>
                        </div>
                        @error('description')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
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
        </script>
    @endpush
</x-app-layout>
