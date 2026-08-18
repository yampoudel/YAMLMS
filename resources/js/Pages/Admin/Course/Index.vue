<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps({
    courses: { type: Object, required: true },
    filters: { type: Object, required: false, default: () => ({}) },
});

const page = usePage();
const currentUser = computed(() => page.props.auth?.user || null);

// Opens search panel on load if '?search' exists in the URL (SSR-safe)
const isSearchOpen = ref(typeof window !== 'undefined' && new URLSearchParams(window.location.search).has('search'));

const form = ref({
    title: props.filters?.title || '',
    status: props.filters?.status || '',
});

const courseList = computed(() => props.courses?.data || []);
const totalCourses = computed(() => props.courses?.total || courseList.value.length || 0);
const paginationLinks = computed(() => props.courses?.links || []);

const handleSearch = () => {
    router.get(route('courses.index'), {
        title: form.value.title || undefined,
        status: form.value.status || undefined,
        search: 1,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteCourse = (id) => {
    if (confirm('Are you sure you want to delete this course?')) {
        router.delete(route('courses.destroy', id), {
            preserveState: true,
            replace: true,
        });
    }
};
</script>

<template>
    <AuthenticatedLayout title="Courses">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
            <!-- Header section -->
            <div class="flex items-center justify-between w-full pb-4 border-b border-gray-200 mb-6">
                <div class="flex gap-4">
                    <Link :href="route('courses.create')"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                        + Add Course
                    </Link>

                    <button @click="isSearchOpen = !isSearchOpen"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm hover:bg-gray-50 transition-all duration-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>
                        <span class="opacity-90">Search</span>
                    </button>
                </div>
            </div>

            <div v-if="isSearchOpen" class="w-full mb-6">
                <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <form @submit.prevent="handleSearch" class="grid gap-4 lg:grid-cols-[1fr_auto] items-end">
                        <div class="grid gap-4 sm:grid-cols-2 flex-1">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course Title</label>
                                <input type="text" id="title" v-model="form.title"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-sm dark:text-white"
                                    placeholder="Search by title" />
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select id="status" v-model="form.status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-sm dark:text-white">
                                    <option value="">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Disabled">Disabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200 cursor-pointer">
                                Search
                            </button>
                            <Link :href="route('courses.index')"
                                class="inline-flex items-center justify-center rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-6 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                                Reset
                            </Link>
                        </div>
                    </form>
                </div>
            </div>

            <FlashNotification />

            <!-- Main content section -->
            <div class="w-full text-gray-600 pl-2 text-sm mb-4">
                Total Courses: <span class="font-bold text-gray-900">{{ totalCourses }}</span>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">S.N.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Course Image</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Course Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price (AUD)</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created By</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">

                        <tr v-for="(course, index) in courseList" :key="course.id ?? index" class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ (props.courses?.from || 1) + index }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center">
                                    <img
                                        :src="course.course_image_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(course.title ?? 'Course')}&color=7F9CF5&background=EBF4FF`"
                                        :alt="course.title"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ course.title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ course.description }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ course.price }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ course.status }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ course.creator?.first_name }} {{ course.creator?.last_name }}</td>
                            <td class="px-4 py-3 text-sm text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <Link :href="route('courses.edit', course.id)" class="text-blue-600 hover:underline font-semibold">Edit</Link>
                                    <button type="button" @click="deleteCourse(course.id)" class="text-red-600 hover:underline font-semibold">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="!courseList.length">
                            <td colspan="8" class="text-center py-10 text-gray-500 text-sm font-medium">No records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination v-if="paginationLinks.length" :links="paginationLinks" />
        </div>
    </AuthenticatedLayout>
</template>
