<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// --- Props ---
const props = defineProps({
    lessons: { type: [Object, Array], required: true },
    filters: { type: Object, required: false, default: () => ({}) },
});

// Opens search panel on load if '?search' exists in the URL (SSR-safe)
const isSearchOpen = ref(typeof window !== 'undefined' && new URLSearchParams(window.location.search).has('search'));

// --- Form State & Watchers ---
const form = ref({
    title: props.filters?.title ?? '',
    status: props.filters?.status ?? '',
});

watch(
    () => props.filters,
    (newFilters) => {
        form.value.title = newFilters?.title ?? '';
        form.value.status = newFilters?.status ?? '';
    },
    { deep: true },
);

// --- Actions ---
const handleSearch = () => {
    router.get(
        route('lessons.index'),
        {
            title: form.value.title || undefined,
            status: form.value.status || undefined,
            search: 1,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

const deleteLesson = (id) => {
    if (confirm('Are you sure you want to delete this lesson?')) {
        router.delete(route('lessons.destroy', id), {
            preserveState: true,
            replace: true,
        });
    }
};

// --- Formatters ---
// Formats the creator's full name cleanly and handles missing names safely
const getCreatorName = (creator) => {
    if (!creator) return 'N/A';

    return [creator.first_name, creator.last_name].filter(Boolean).join(' ') || 'N/A';
};

// Computed list properties
const lessonList = computed(() => (Array.isArray(props.lessons) ? props.lessons : (props.lessons?.data ?? [])));
const totalLessons = computed(() => props.lessons?.total ?? lessonList.value.length ?? 0);
const paginationLinks = computed(() => (Array.isArray(props.lessons?.links) ? props.lessons.links : []));
</script>

<template>
    <!-- Main Page Layout Wrapper -->
    <AuthenticatedLayout title="Lessons">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Page Header / Actions -->
            <div class="flex flex-col gap-4 px-6 py-5 border-b border-gray-200 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Lessons</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage lesson modifications, additions, and removals while tracking course mappings and publication statuses.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Add Lesson Button -->
                    <Link
                        :href="route('lessons.create')"
                        class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200">
                        + Add Lesson
                    </Link>

                    <!-- Search Toggle Button -->
                    <button
                        @click="isSearchOpen = !isSearchOpen"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer">
                        <svg xmlns="http://w3.org" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>
                        <span>{{ isSearchOpen ? 'Hide Search' : 'Search' }}</span>
                    </button>
                </div>
            </div>

            <!-- Search Filters -->
            <div v-if="isSearchOpen" class="px-6 py-5 border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/40">
                <form @submit.prevent="handleSearch" class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
                    <!-- Title Filter -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lesson Title</label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            placeholder="Search by title..." />
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select
                            id="status"
                            v-model="form.status"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            <option value="">All</option>
                            <option value="Active">Active</option>
                            <option value="Disabled">Disabled</option>
                        </select>
                    </div>

                    <!-- Filter Control Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200 cursor-pointer">
                            Search
                        </button>
                        <Link
                            :href="route('lessons.index')"
                            class="inline-flex items-center justify-center rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-6 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                            Reset
                        </Link>
                    </div>
                </form>
            </div>

            <!-- Flash Notification -->
            <div class="px-6 pt-5">
                <FlashNotification />
            </div>

            <!-- Results Summary -->
            <div class="flex items-center justify-between px-6 py-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Total Lessons:
                    <span class="font-bold text-gray-900 dark:text-white">{{ totalLessons }}</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="px-6 pb-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full min-w-[1000px] divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="w-16 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">S.N.</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Lesson Title</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Course Title</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Created By</th>
                                <th class="w-48 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            <template v-if="lessonList.length">
                                <tr v-for="(lesson, index) in lessonList" :key="lesson.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ (props.lessons?.from ?? 1) + index }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ lesson.title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">{{ lesson.description }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ lesson.course?.title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ getCreatorName(lesson.creator) }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-medium space-x-3">
                                        <!-- Edit Lesson Action Link -->
                                        <Link
                                            :href="route('lessons.edit', lesson.id)"
                                            class="font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline">
                                            Edit
                                        </Link>
                                        <button
                                            type="button"
                                            @click="deleteLesson(lesson.id)"
                                            class="font-semibold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:underline cursor-pointer">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-sm font-medium text-gray-500 dark:text-gray-400">No records found.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Layout Anchor -->
            <div v-if="paginationLinks.length" class="px-6 pb-6">
                <Pagination :links="paginationLinks" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
