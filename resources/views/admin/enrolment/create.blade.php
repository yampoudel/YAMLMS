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
                <form action="{{ route('enrolments.store', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p><strong>Please select course from drop down to assign the course to this user:</strong>
                            </p>
                            <p>User ID : {{ $user->id }}
                            <p>
                            <p>First Name: {{ $user->first_name }}
                            <p>
                            <p>Lastname: {{ $user->last_name }}
                            <p>
                            <p>Email: {{ $user->email }}
                            <p>
                        </div>
                        <!-- Title -->
                        <div>
                            <label for='select_course' class="block text-sm font-medium text-gray-700 mb-1"> Select
                                Course
                                </lablel>
                                <select name="course_id" id="course_id">
                                    @foreach ($courses as $course)
                                        <option value='{{ $course->id }}'>{{ $course->title }}</option>
                                    @endforeach
                                </select>
                        </div>
                        @error('course_id')
                            <span class="text-red-700">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Course Enrol
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
