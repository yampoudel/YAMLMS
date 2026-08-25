<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

// --- Props ---
const props = defineProps({
    user: { type: Object, required: true },
    courses: { type: [Object, Array], required: true },
    page_info: { type: Object, required: true },
    button_label: { type: String, required: true },
});

// --- Computed List Setup ---
const courseList = computed(() => (Array.isArray(props.courses) ? props.courses : (props.courses?.data ?? [])));

// --- Form State ---
const form = useForm({
    course_id: courseList.value[0]?.id ?? null,
});

// --- Form Submission ---
const submitForm = () => {
    form.post(route('enrolments.store', props.user.id), {
        preserveScroll: true,
        onError: (errors) => {
            console.log('Validation pipeline failure detected:', errors);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
    });
};

// UI text labels
const title = props.page_info?.title ?? 'Enrol User';
const back_button = props.page_info?.back_button ?? 'Back to Users';
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Header Section Bar -->
            <header class="bg-white border-b border-gray-200 py-6 mb-6 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between w-full">
                    <div class="flex-1 flex justify-start">
                        <Link
                            :href="route('users.index')"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ back_button }}
                        </Link>
                    </div>
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                            {{ title }}
                        </h1>
                    </div>
                    <div class="flex-1"></div>
                </div>
            </header>

            <!-- Main Form Card Container -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: User Information Summary Display -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h2 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wider">Target Enrolment Profile</h2>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>
                                    <span class="font-semibold text-gray-900">User ID:</span>
                                    {{ user.id }}
                                </p>
                                <p>
                                    <span class="font-semibold text-gray-900">First Name:</span>
                                    {{ user.first_name }}
                                </p>
                                <p>
                                    <span class="font-semibold text-gray-900">Last Name:</span>
                                    {{ user.last_name }}
                                </p>
                                <p>
                                    <span class="font-semibold text-gray-900">Email Address:</span>
                                    {{ user.email }}
                                </p>
                            </div>
                        </div>

                        <!-- Right Column: Course Dropdown Selection Input -->
                        <div>
                            <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Select Target Course</label>
                            <select
                                id="course_id"
                                v-model="form.course_id"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm bg-white">
                                <option :value="null" disabled>Select an available course</option>
                                <option v-for="course in courseList" :key="course.id" :value="course.id">
                                    {{ course.title }}
                                </option>
                            </select>
                            <span v-if="form.errors.course_id" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.course_id }}
                            </span>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex flex-col gap-4 pt-8 border-t border-gray-200 mt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link
                            :href="route('users.index')"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            Cancel
                        </Link>
                        <Button type="submit" :button_label="button_label" :processing="form.processing" />
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
