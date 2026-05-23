<x-app-layout>
    {{-- Main Wrapper: Forced Light Mode --}}
    <div class="flex h-screen overflow-hidden light" style="background-color: #ffffff !important; color: #111827 !important;">

        {{-- MAIN CONTENT (Left Side) --}}
        <div class="flex-1 flex flex-col h-full overflow-y-auto bg-white shadow-inner">
            <div class="max-w-4xl mx-auto px-10 py-16 w-full">

                {{-- Header --}}
                <header class="mb-12">
                    <div class="flex items-center space-x-2 text-[14px] font-black uppercase tracking-[0.2em] mb-4">
                        <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:underline">Dashboard</a>

                        <span class="text-gray-300">/</span>

                        <span class="text-gray-400 font-medium italic normal-case tracking-normal">
                            Learning Mode
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-[1.1] tracking-tight">
                        {{ $current_lesson->title }}
                    </h1>
                </header>

                {{-- LESSON CONTENT: Using @forelse for the Nested JSON array --}}
                <article class="prose prose-slate lg:prose-xl max-w-none mb-24 prose-headings:text-slate-900 prose-p:text-slate-900">
                    @php
                        $contentBlocks = is_array($current_lesson->content)
                            ? $current_lesson->content
                            : json_decode($current_lesson->content, true);
                    @endphp

                    @forelse($contentBlocks as $block)
                        <div class="mb-8 leading-relaxed text-slate-900">
                            {!! $block['value'] ?? '' !!}
                        </div>
                    @empty
                        <p class="text-gray-400 italic">No content available for this lesson.</p>
                    @endforelse
                </article>

                {{-- Complete & Next --}}
                <div class="mt-20 pt-12 border-t border-gray-100 mb-40 flex justify-end">
                    <form action="{{ route('lessons.complete', [$course, $current_lesson]) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-8 py-5 rounded-full font-black text-sm uppercase tracking-widest text-white bg-indigo-600 shadow-2xl hover:bg-indigo-700 transition-all active:scale-95">
                            Complete & Next Lesson →
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TIMELINE SIDEBAR (Right Side) --}}
        <aside class="w-[400px] flex flex-col border-l border-gray-200 h-full shadow-2xl relative z-10 bg-gray-50">

            {{-- Course Progress Header --}}
            <div class="p-8 bg-white border-b border-gray-100 shadow-sm">
                <h2 class="font-black text-xl leading-tight mb-4 text-slate-900">{{ $course->title }}</h2>

                @php
                    $progress = auth()->user()->courseProgress->where('course_id', $course->id)->first();
                    $percent = $progress->progress_percentage ?? 0;
                    $completed_ids = auth()->user()->completedLessons->pluck('lesson_id')->toArray();
                @endphp

                <div class="space-y-2">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <span>Course Progress</span>
                        <span class="text-indigo-600">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden border border-gray-50">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out bg-indigo-600"
                            style="width: {{ $percent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Lesson Timeline --}}
            <nav class="flex-1 overflow-y-auto py-6">
                @foreach($lessons as $index => $lesson)
                    @php
                        $isCurrent = $current_lesson->id === $lesson->id;
                        $isFinished = in_array($lesson->id, $completed_ids);
                    @endphp

                    <a href="{{ route('lessons.play', [$course, $lesson]) }}"
                    class="group flex items-start px-8 py-6 transition-all relative
                    {{ $isCurrent ? 'bg-white shadow-inner' : 'hover:bg-white' }}">

                        {{-- Vertical Connector Line --}}
                        @if(!$loop->last)
                            <div class="absolute left-[47px] top-14 bottom-0 w-0.5 bg-gray-200"></div>
                        @endif

                        {{-- STATUS INDICATOR (TICK / RING) --}}
                        <div class="relative z-20 flex-shrink-0 mt-0.5">
                            @if($isFinished)
                                {{-- COMPLETED: GREEN TICK --}}
                                <div class="w-6 h-6 rounded-full flex items-center justify-center shadow-md border-2 border-white bg-green-500">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @elseif($isCurrent)
                                {{-- CURRENT: INDIGO RING --}}
                                <div class="w-6 h-6 bg-white border-[6px] border-indigo-600 rounded-full shadow-md"></div>
                            @else
                                {{-- UPCOMING: EMPTY GRAY RING --}}
                                <div class="w-6 h-6 bg-white border-2 border-gray-300 rounded-full group-hover:border-indigo-400 transition-all"></div>
                            @endif
                        </div>

                        {{-- Lesson Text --}}
                        <div class="ml-6">
                            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 mb-1">
                                Lesson {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <span class="text-[15px] leading-tight transition-colors
                                {{ $isCurrent ? 'font-black underline decoration-indigo-200 decoration-4 underline-offset-4 text-indigo-950' : 'text-gray-600 font-bold group-hover:text-gray-900' }}">
                                {{ $lesson->title }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </nav>
        </aside>
    </div>
</x-app-layout>
