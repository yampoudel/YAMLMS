<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

const props = defineProps({
    course: { type: Object, required: true },
    lessons: { type: Object, required: true },
    page_info: { type: Object, required: true },
    button_label: { type: String, required: true },
});

// Setup form object (uses _method spoofing for safe multi-part file uploads)
const form = useForm({
    title: props.course.title,
    description: props.course.description,
    price: props.course.price,
    status: props.course.status,
    image_path: undefined,
    _method: 'PUT',
});

const submitForm = () => {
    const courseId = props.course.id;
    if (!courseId) return;

    // Converted to POST with PUT method spoofing to prevent Laravel upload failures
    form.post(route('courses.update', { course: courseId }), {
        preserveScroll: true,
    });
};

// Image configuration variables matching your User component structure
const imageError = ref('');
const previewImage = ref(props.course.course_image_url ?? '');

const handleImagePreview = (e) => {
    imageError.value = '';
    const file = e.target.files?.[0];

    // Case 1: File selection cancelled
    if (!file) {
        form.image_path = null;
        previewImage.value = props.course.course_image_url ?? '';
        return;
    }

    // Case 2: Validation for raw file format type
    if (!file.type.startsWith('image/')) {
        imageError.value = 'The file must be an image.';
        e.target.value = '';
        form.image_path = null;
        return;
    }

    // Case 3: Extension string validation check
    const fileExtension = file.name.toLowerCase().split('.').pop();
    if (!['png', 'jpg', 'jpeg', 'webp'].includes(fileExtension)) {
        imageError.value = 'The image must be a file of type: png, jpg, jpeg, webp.';
        e.target.value = '';
        form.image_path = null;
        return;
    }

    // Case 4: File size boundary check (2MB limits)
    if (file.size > 2048 * 1024) {
        imageError.value = 'The image must not be greater than 2MB.';
        e.target.value = '';
        form.image_path = null;
        previewImage.value = props.course.course_image_url ?? '';
        return;
    }

    // Execution: Convert binary stream to a local visual text string wrapper
    const reader = new FileReader();
    reader.onload = (event) => { previewImage.value = event.target?.result; };
    reader.readAsDataURL(file);
    form.image_path = file;
};

// Template constants
const title = props.page_info.title ?? 'Edit Course';
const back_button = props.page_info.back_button ?? 'Back To Courses';
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <header class="bg-white border-b border-gray-200 py-6 mb-6 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between w-full">
                    <div class="flex-1 flex justify-start">
                       <Link :href="route('courses.index')"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <!-- SVG Icon makes the "Back" action clear -->
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ back_button }}
                        </Link>
                    </div>

                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ title}}
                        </h1>
                    </div>

                    <div class="flex-1 flex justify-end">
                        <Link :href="route('lessons.create', { course_id: props.course.id })"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                            {{ page_info.lesson_link || 'Add Lesson' }}
                        </Link>
                    </div>
                </div>
            </header>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-base font-semibold text-gray-900 mb-3">Title</label>
                            <input type="text" name="title" id="title" v-model="form.title" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <span v-if="form.errors.title" class="text-red-700 text-sm block mt-2">{{ form.errors.title }}</span>
                        </div>

                        <!-- Course Description -->
                        <div>
                            <label for="description" class="block text-base font-semibold text-gray-900 mb-3">Course Description</label>
                            <textarea name="description" id="description" maxlength="500" v-model="form.description"
                             class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent h-20 resize-none">
                            </textarea>
                            <span v-if="form.errors.description">{{ form.errors.description }}</span>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for='price' class="block text-base font-semibold text-gray-900 mb-3">Course Price</label>
                            <input type='number' name='price' id='price' v-model="form.price" step='0.01' min='0'
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <span v-if="form.errors.price" class="text-red-700 text-sm block mt-2">{{ form.errors.price }}</span>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for='status' class="block text-base font-semibold text-gray-900 mb-3">Course Status</label>
                            <select name="status" id="status" v-model="form.status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                            <span v-if="form.errors.status" class="text-red-700 text-sm block mt-2">{{ form.errors.status }}</span>
                        </div>

                        <!-- Course Image -->
                        <div>
                            <label for="course_image_path" class="block text-base font-semibold text-gray-900 mb-3">Course Image</label>
                            <input type="file" name="image_path" id='course_image_path' @change="handleImagePreview" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p v-if="form.errors.image_path || imageError" class="text-red-700 text-sm block mt-2 font-medium">{{ form.errors.image_path || imageError }}</p>
                            <div v-if="previewImage" class="mt-4">
                                <img :src="previewImage" alt="Preview" class="h-32 w-32 object-cover rounded-md border" />
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                     <div class="flex flex-col gap-4 pt-8 border-gray-200 mt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link :href="route('courses.index')"
                         class="items-center justify-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                          Cancel
                        </Link>
                        <Button :button_label="button_label" :processing="form.processing"/>
                     </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
