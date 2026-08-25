<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// --- Props ---
const props = defineProps({
    courses: { type: [Object, Array], required: true },
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
        route('courses.index'),
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

const deleteCourse = (id) => {
    if (confirm('Are you sure you want to delete this course?')) {
        router.delete(route('courses.destroy', id), {
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
const courseList = computed(() => (Array.isArray(props.courses) ? props.courses : (props.courses?.data ?? [])));
const totalCourses = computed(() => props.courses?.total ?? courseList.value.length ?? 0);
const paginationLinks = computed(() => (Array.isArray(props.courses?.links) ? props.courses.links : [])); // Fixed: Bound safely to props.courses
</script>

<template>
    <!-- Main Page Layout Wrapper -->
    <AuthenticatedLayout title="Courses">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Page Header / Actions -->
            <!-- Page Header / Actions -->
            <div class="flex flex-col gap-4 px-6 py-5 border-b border-gray-200 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Courses</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage course creation, modifications, and deletions while seamlessly building out internal lesson structures.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Add Course Button -->
                    <Link
                        :href="route('courses.create')"
                        class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200">
                        + Add Course
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
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course Title</label>
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
                            :href="route('courses.index')"
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
                    Total Courses:
                    <span class="font-bold text-gray-900 dark:text-white">{{ totalCourses }}</span>
                </div>
            </div>
            <!-- Main Content -->
            <div class="px-6 pb-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full min-w-[1000px] divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="w-16 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">S.N.</th>
                                <th class="w-24 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Course Image</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Course Title</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Description</th>
                                <th class="w-32 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Price (AUD)</th>
                                <th class="w-28 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Created By</th>
                                <th class="w-32 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            <template v-if="courseList.length">
                                <tr v-for="(course, index) in courseList" :key="course.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ (props.courses?.from ?? 1) + index }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                                            <!-- Fixed fallback avatar string logic pattern -->
                                            <img
                                                :src="course.course_image_url || `https://ui-avatars.com{encodeURIComponent(course.title ?? 'Course')}&color=7F9CF5&background=EBF4FF`"
                                                :alt="course.title + ' Thumbnail'"
                                                class="w-full h-full object-cover"
                                                loading="lazy" />
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ course.title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ course.description }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 font-mono">{{ course.price }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span
                                            :class="
                                                course.status === 'Active'
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                            "
                                            class="px-2.5 py-1 rounded-full text-xs font-medium">
                                            {{ course.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ getCreatorName(course.creator) }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <div class="flex items-center justify-center gap-3 whitespace-nowrap">
                                            <Link :href="route('courses.edit', course.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold">
                                                Edit
                                            </Link>
                                            <button
                                                type="button"
                                                @click="deleteCourse(course.id)"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-semibold cursor-pointer">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="8" class="px-4 py-10 text-center text-sm font-medium text-gray-500 dark:text-gray-400">No courses found matching your criteria.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table Pagination Footer -->
            <div class="px-6 pb-6">
                <Pagination v-if="paginationLinks.length" :links="paginationLinks" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
