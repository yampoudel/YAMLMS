<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">My Learning Journey</h3>

    @if($enrolled_courses->isEmpty())
        <div class="bg-blue-50 border border-blue-200 p-8 rounded-xl text-center">
            <p class="text-blue-700 font-medium">You aren't enrolled in any courses yet.</p>
            <a href="{{ route('courses.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-full text-sm font-bold">Browse Courses</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrolled_courses as $course)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    {{-- Course Image placeholder --}}
                    <div class="h-32 bg-indigo-600 flex items-center justify-center">
                        <span class="text-white text-3xl font-bold">{{ substr($course->title, 0, 1) }}</span>
                    </div>

                    <div class="p-5">
                        <h4 class="font-bold text-gray-900 mb-1 truncate">{{ $course->title }}</h4>
                        <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $course->description }}</p>
                        
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                            <span class="text-xs font-medium text-gray-400">{{ $course->lessons->count() }} Lessons</span>
                            <a href="{{ route('courses.show', $course) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">
                                Resume →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
