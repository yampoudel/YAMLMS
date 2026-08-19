<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    lessons: { type: [Object, Array], required: true },
    filters: { type: Object, required: false, default: () => ({}) },
});

const page = usePage();
const currentUser = computed(() => page.props.auth?.user || null);

const isSearchOpen = ref(typeof window !== 'undefined' && new URLSearchParams(window.location.search).has('search'));

const form = ref({
    title: props.filters?.title || '',
    status: props.filters?.status || '',
});

const syncFormFromFilters = (newFilters = {}) => {
    form.value.title = newFilters.title ?? '';
    form.value.status = newFilters.status ?? '';
};

watch(() => props.filters, (newFilters) => {
    syncFormFromFilters(newFilters || {});
}, { deep: true });

const lessonList = computed(() =>
    Array.isArray(props.lessons) ? props.lessons : (props.lessons?.data || [])
);

const totalLessons = computed(() =>
    Array.isArray(props.lessons) ? props.lessons.length : (props.lessons?.total || lessonList.value.length || 0)
);

const paginationLinks = computed(() =>
    Array.isArray(props.lessons) ? [] : (props.lessons?.links || [])
);

const handleSearch = () => {
    isSearchOpen.value = true;
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
        }
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

const resetSearch = () => {
    form.value.title = '';
    form.value.status = '';
    isSearchOpen.value = false;
    router.get(route('lessons.index'), {}, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">

            <div class="mb-6 flex w-full items-center justify-between border-b border-gray-200 pb-4">
                <div class="flex gap-4">
                    <Link
                        :href="route('lessons.create')"
                        class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-blue-700"
                    >
                        + Add Lesson
                    </Link>

                    <button
                        type="button"
                        @click="isSearchOpen = !isSearchOpen"
                        class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>
                        <span class="opacity-90">Search</span>
                    </button>
                </div>
            </div>

            <div v-if="isSearchOpen" class="mb-6 w-full">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                    <form
                        @submit.prevent="handleSearch"
                        class="grid items-end gap-4 lg:grid-cols-[1fr_auto]"
                    >
                        <div class="grid flex-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label
                                    for="title"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Lesson Title
                                </label>
                                <input
                                    id="title"
                                    v-model.trim="form.title"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 bg-white p-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    placeholder="Search by title"
                                />
                            </div>

                            <div>
                                <label
                                    for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Status
                                </label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 bg-white p-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                                    <option value="">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Disabled">Disabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="submit"
                                class="inline-flex cursor-pointer items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-indigo-700"
                            >
                                Search
                            </button>
                            <Link
                                :href="route('lessons.index')"
                                class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 transition-all duration-200 hover:bg-gray-100"
                            >
                                Reset
                            </Link>
                        </div>
                    </form>
                </div>
            </div>

            <FlashNotification />

            <div class="w-full text-gray-600 pl-2 text-sm mb-4">
                Total Lessons: <span class="font-bold text-gray-900">{{ totalLessons }}</span>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">S.N.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lesson Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Course Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created By</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="(lesson, index) in lessonList" :key="lesson.id ?? index" class="transition-colors duration-150 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ (props.lessons?.from || 1) + index }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ lesson.title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ lesson.description }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ lesson.course.title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ lesson.creator.first_name }} {{ lesson.creator.last_name }}</td>
                            <td class="px-4 py-3 text-center text-sm">
                                <div class="flex items-center justify-center gap-3">
                                    <Link :href="route('lessons.edit', lesson.id)" class="font-semibold text-blue-600 hover:underline">
                                        Edit
                                    </Link>
                                    <button type="button" @click="deleteLesson(lesson.id)" class="font-semibold text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="!lessonList.length">
                            <td colspan="6" class="text-center py-10 text-gray-500 text-sm font-medium">No records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination v-if="paginationLinks.length" :links="paginationLinks" />
        </div>
    </AuthenticatedLayout>
</template>
