<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// --- Props ---
const props = defineProps({
    course: { type: Object, required: true },
    current_lesson: { type: Object, required: true },
    lessons: { type: [Object, Array], required: true },
});

// --- Emits ---
const emit = defineEmits(['navigate-dashboard', 'select-lesson']);

// --- Dynamic Progress Tracking ---
const courseProgressPercent = computed(() => {
    // Reads directly from your backend calculator service safely
    return props.course?.progress_percentage ?? 0;
});

// --- Methods / Helpers ---
const isCurrentLesson = (id) => props.current_lesson?.id === id;

const isLessonFinished = (id) => {
    const foundLesson = props.lessons.find((l) => l.id === id);
    return foundLesson?.is_completed || false;
};

const formatIndex = (index) => {
    return String(index + 1).padStart(2, '0');
};

const navigateToDashboard = () => {
    emit('navigate-dashboard');
    router.visit(route('dashboard'));
};

const selectLesson = (lesson) => {
    router.visit(route('lessons.play', { course: props.course.id, lesson: lesson.id }));
};

const completeAndNext = () => {
    // Trigger the backend to mark the current lesson as complete and handle progress updates
    router.post(
        route('lessons.complete', { course: props.course.id, lesson: props.current_lesson.id }),
        {},
        {
            preserveState: false, // Discard local component caches to capture updated database states
            preserveScroll: false, // Return window position cleanly to the top for the next lesson
            onError: (errors) => console.error('Complete action encountered an issue:', errors),
        },
    );
};

// --- Content Parsing & UI Computed Values ---
const contentBlocks = computed(() => {
    if (!props.current_lesson?.content) return [];
    if (Array.isArray(props.current_lesson.content)) {
        return props.current_lesson.content;
    }
    try {
        return JSON.parse(props.current_lesson.content);
    } catch (e) {
        console.error('Failed to parse lesson content JSON:', e);
        return [];
    }
});

const completeAndNextLabel = computed(() => {
    const currentIndex = props.lessons.findIndex((l) => l.id === props.current_lesson.id);
    return currentIndex === props.lessons.length - 1 ? 'Complete Course ✓' : 'Complete & Next Lesson →';
});

const lessonTitle = computed(() => props.current_lesson?.title || 'Untitled Lesson');
</script>

<template>
    <AuthenticatedLayout :title="lessonTitle">
        <div :key="props.current_lesson.id" class="flex h-screen overflow-hidden light" style="background-color: #ffffff !important; color: #111827 !important">
            <!-- Main lesson content -->
            <div class="flex-1 flex flex-col h-full overflow-y-auto bg-white shadow-inner">
                <div class="max-w-4xl mx-auto px-10 py-16 w-full">
                    <header class="mb-12">
                        <div class="flex items-center space-x-2 text-[14px] font-black uppercase tracking-[0.2em] mb-4">
                            <button @click="navigateToDashboard" class="text-indigo-600 hover:underline">Dashboard</button>
                            <span class="text-gray-300">/</span>
                            <span class="text-gray-400 font-medium italic normal-case tracking-normal">Learning Mode</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-[1.1] tracking-tight">
                            {{ lessonTitle }}
                        </h1>
                    </header>

                    <article class="prose prose-slate lg:prose-xl max-w-none mb-24 prose-headings:text-slate-900 prose-p:text-slate-900">
                        <template v-if="contentBlocks && contentBlocks.length > 0">
                            <div v-for="(block, idx) in contentBlocks" :key="idx" class="mb-8 leading-relaxed text-slate-900" v-html="block.value || ''"></div>
                        </template>
                        <p v-else class="text-gray-400 italic">No content available for this lesson.</p>
                    </article>

                    <div class="mt-20 pt-12 border-t border-gray-100 mb-40 flex justify-end">
                        <button
                            @click="completeAndNext"
                            type="button"
                            class="inline-flex items-center px-8 py-5 rounded-full font-black text-sm uppercase tracking-widest text-white bg-indigo-600 shadow-2xl hover:bg-indigo-700 transition-all active:scale-95">
                            {{ completeAndNextLabel }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course lesson navigation -->
            <aside class="w-[400px] flex flex-col border-l border-gray-200 h-full shadow-2xl relative z-10 bg-gray-50">
                <div class="p-8 bg-white border-b border-gray-100 shadow-sm">
                    <h2 class="font-black text-xl leading-tight mb-4 text-slate-900">
                        {{ course.title }}
                    </h2>

                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span>Course Progress</span>
                            <span class="text-indigo-600">{{ courseProgressPercent }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden border border-gray-50">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out bg-indigo-600" :style="{ width: courseProgressPercent + '%' }"></div>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 overflow-y-auto py-6">
                    <button
                        v-for="(lesson, index) in lessons"
                        :key="lesson.id"
                        @click="selectLesson(lesson)"
                        class="w-full text-left group flex items-start px-8 py-6 transition-all relative"
                        :class="isCurrentLesson(lesson.id) ? 'bg-white shadow-inner' : 'hover:bg-white'">
                        <div v-if="index !== lessons.length - 1" class="absolute left-[47px] top-14 bottom-0 w-0.5 bg-gray-200"></div>

                        <div class="relative z-20 flex-shrink-0 mt-0.5">
                            <div v-if="isLessonFinished(lesson.id)" class="w-6 h-6 rounded-full flex items-center justify-center shadow-md border-2 border-white bg-green-500">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>

                            <div v-else-if="isCurrentLesson(lesson.id)" class="w-6 h-6 bg-white border-[6px] border-indigo-600 rounded-full shadow-md"></div>

                            <div v-else class="w-6 h-6 bg-white border-2 border-gray-300 rounded-full group-hover:border-indigo-400 transition-all"></div>
                        </div>

                        <div class="ml-6">
                            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 mb-1">Lesson {{ formatIndex(index) }}</p>
                            <h3
                                class="font-bold text-sm tracking-tight transition-colors duration-150"
                                :class="isCurrentLesson(lesson.id) ? 'text-indigo-600' : 'text-slate-700 group-hover:text-slate-900'">
                                {{ lesson.title }}
                            </h3>
                        </div>
                    </button>
                </nav>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
