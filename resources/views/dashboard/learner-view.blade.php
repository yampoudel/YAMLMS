<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">My Learning Journey</h3>

    @if($enrolled_courses->isEmpty())
        <div class="bg-blue-50 border border-blue-200 p-8 mb-8 rounded-xl text-center">
            <p class="text-blue-700 font-medium">You aren't enrolled in any courses yet.</p>
        </div>
        <div class= 'text-center'>
            <a href="{{ route('courses.index') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200"> Browse Course</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrolled_courses as $course)
                {{-- Get the progress record for this course --}}
                @php
                    $progress = auth()->user()->courseProgress->where('course_id', $course->id)->first();
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col">
                    <div class="h-32 bg-indigo-600 flex items-center justify-center relative">
                        <span class="text-white text-3xl font-bold">{{ substr($course->title, 0, 1) }}</span>
                        
                        {{-- Show a progress percentage badge if they've started --}}
                        @if($progress && $progress->progress_percentage > 0)
                            <div class="absolute bottom-2 right-2 bg-white/90 px-2 py-1 rounded-lg text-[10px] font-bold text-indigo-700">
                                {{ $progress->progress_percentage }}% Done
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h4 class="font-bold text-gray-900 mb-1 px-4 truncate">{{ $course->title }}</h4>
                        <p class="text-xs text-gray-500 mb-4 px-4 line-clamp-2">{{ $course->description }}</p>
                        
                        <div class="mt-auto">
                            {{-- Show progress bar --}}
                            <div class="w-full bg-gray-100 h-1.5 rounded-full mb-4">
                                <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $progress->progress_percentage ?? 0 }}%"></div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                <span class="text-xs px-4 py-2 font-medium text-gray-400">{{ $course->lessons->count() }} Lessons</span>
                                
                                {{-- Start or Resume --}}
                                @if(!$progress || $progress->status === 'Not Started')
                                    <a href="{{ route('lessons.start', $course) }}" class="text-sm px-4 py-2 font-bold text-green-600 hover:text-green-700">
                                        Start Course →
                                    </a>
                                @else
                                    <a href="{{ route('lessons.play', $course) }}" class="text-sm px-4 py-2 font-bold text-indigo-600 hover:text-indigo-800">
                                        {{ $progress->status === 'Completed' ? 'Review' : 'Resume' }} →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
