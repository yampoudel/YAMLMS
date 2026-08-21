<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

const props = defineProps({
    user: { type: Object, required: true },
    courses: { type: [Object, Array], required: true },
    page_info: { type: Object, required: true },
    button_label: { type: String, required: true },
});

// Support both paginated and plain course responses from the controller.
const courseList = computed(() => {
    if (Array.isArray(props.courses)) {
        return props.courses;
    }

    return props.courses?.data ?? [];
});

// Preselect the first available course for a faster enrolment workflow.
const form = useForm({
    course_id: courseList.value[0]?.id ?? null,
});

const title = props.page_info?.title ?? 'Enrol User';
const back_button = props.page_info?.back_button ?? 'Back to Users';

// Submit the enrolment and preserve the current page position on validation errors.
const submitForm = () => {
    form.post(route('enrolments.store', props.user.id), {
        preserveScroll: true,
        onError: () => window.scrollTo({ top: 0, behavior: 'smooth' }),
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <header class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center justify-start">
                        <Link
                            :href="route('users.index')"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:bg-slate-200 focus:outline-none focus:ring-4 focus:ring-slate-200"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>{{ back_button }}</span>
                        </Link>
                    </div>

                    <div class="flex-1 text-center sm:px-4">
                        <h1 id="page-title" class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                            {{ title }}
                        </h1>
                    </div>

                    <div class="hidden sm:block sm:w-32" aria-hidden="true"></div>
                </div>
            </header>

            <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div>
                            <p class="mb-3 text-base font-semibold text-gray-900">
                                Please select a course to assign to this user:
                            </p>
                            <div class="space-y-1 text-gray-700">
                                <p>User ID: {{ user.id }}</p>
                                <p>First Name: {{ user.first_name }}</p>
                                <p>Lastname: {{ user.last_name }}</p>
                                <p>Email: {{ user.email }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="course_id" class="mb-3 block text-base font-semibold text-gray-900">
                                Select Course
                            </label>
                            <select
                                v-model="form.course_id"
                                id="course_id"
                                name="course_id"
                                required
                                :aria-invalid="Boolean(form.errors.course_id)"
                                aria-describedby="course-id-error"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option :value="null" disabled>Select a course</option>
                                <option
                                    v-for="course in courseList"
                                    :key="course.id"
                                    :value="course.id"
                                >
                                    {{ course.title }}
                                </option>
                            </select>

                            <span
                                v-if="form.errors.course_id"
                                id="course-id-error"
                                class="mt-2 block text-sm text-red-700"
                                role="alert"
                            >
                                {{ form.errors.course_id }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-end gap-4 border-t border-gray-200 pt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link
                            :href="route('users.index')"
                            class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-gray-100 px-6 py-2.5 text-sm font-semibold text-gray-700 transition duration-200 hover:bg-gray-200"
                        >
                            Cancel
                        </Link>
                        <Button
                            type="submit"
                            :button_label="button_label"
                            :processing="form.processing"
                        />
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
