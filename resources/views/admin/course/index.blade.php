<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <!-- SECTION 1: Extreme Left -->
            <div class="flex-1 flex justify-start gap-4">
                <a href="{{ route('courses.create') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                    + Add Course
                </a>
                <a href="{{ route('courses.index', array_merge(request()->all(), ['search' => 1])) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm hover:bg-gray-50 transition-all duration-200">
                    <!-- Magnifying glass icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                    </svg>
                    <span class="opacity-90">Search</span>
                </a>
            </div>

            <!-- SECTION 2: Center (The Title) -->
            <div class="flex-1 text-center">
                <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                    Courses
                </h1>
            </div>

            <!-- SECTION 1: Right Spacer (Keeps the title perfectly in the middle) -->
            <div class="flex-1">
            </div>
        </div>
    {{-- Search Section --}}
    @if(request()->has('search'))
    <div class="w-full px-4 mt-4">
        <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <form action="{{ route('courses.index') }}" method="GET" class="grid gap-4 lg:grid-cols-[1fr_auto] items-end">
                <div class="grid gap-4 sm:grid-cols-2 flex-1">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Course Title</label>
                        <input type="text" name="title" id="title" value="{{ request('title') }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Search by title">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>All</option>
                            <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Disabled" {{ request('status') === 'Disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" name="search" value="1"
                        class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition-all duration-200">
                        Search
                    </button>
                    <a href="{{ route('courses.index') }}"
                        class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-100 transition-all duration-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endif
    </x-slot>

    {{-- Main Content --}}
    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                @if (session('success') || session('error'))
                    <div
                        class="notify_message mb-4 p-3 border rounded {{ session('success') ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                        {{ session('success') ?? session('error') }}
                    </div>
                @endif

                <div class="bg-white shadow-sm sm:rounded-lg p-4 overflow-x-auto">

                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">S.N.</th>
                                <th class="px-4 py-2 border">Course Image</th>
                                <th class="px-4 py-2 border">Course Title</th>
                                <th class="px-4 py-2 border">Course Description</th>
                                <th class="px-4 py-2 border">Course Price(AUD)</th>
                                <th class="px-4 py-2 border">Course Status</th>
                                <th class="px-4 py-2 border">Created By</th>
                                <th class="px-4 py-2 border" colspan="2">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($courses as $index => $course)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border"><img src="{{ $course->course_image_url }}" alt="{{ $course->title }}"
                                        class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                                    </td>
                                    <td class="px-4 py-2 border">{{ $course->title }}</td>
                                    <td class="px-4 py-2 border">{{ $course->description }}</td>
                                    <td class="px-4 py-2 border">{{ $course->price}}</td>
                                    <td class="px-4 py-2 border">{{ $course->status}}</td>
                                    <td class="px-4 py-2 border">{{ $course->creator->name}}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('courses.edit', $course->id) }}"
                                            class="text-blue-600 hover:underline">
                                            Edit
                                        </a>
                                    </td>

                                    <td class="px-4 py-2 border">
                                        <form action="{{ route('courses.destroy', $course) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class='mt-4'>
                        {{ $courses->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                // Select all messages in case there are multiple
                const messages = document.querySelectorAll('.notify_message');

                messages.forEach(el => {
                    el.style.transition = "opacity 0.8s ease";
                    el.style.opacity = "0";

                    setTimeout(() => {
                        el.remove();
                    }, 800);
                });
            }, 3000);
        </script>
    @endpush
</x-app-layout>
