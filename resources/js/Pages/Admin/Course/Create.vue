<script setup>
import { Link, useForm } from '@inertiajs/vue3';
// Needed when route need to inject in script
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

// Ingest ALL properties from controller, including dynamic button label
const props = defineProps({
    page_info: { type: Object, required: true },
    button_label: { type: String, required: true },
    courses: { type: Object, required: true },
});

// Initialize Inertia's form tracking matrix
const form = useForm({
    title: '',
    description: '',
    price: '',
    status: 'Active',
    image_path: null
});

// Monitor physical input field alterations and map files safely into state memory
const handleImageUpload = (event) => {
    form.image_path = event.target.files[0];
};

// Fire structured inputs straight through the single-page application routing engine
const submitForm = () => {
    form.post(route('courses.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section Block Frame Wrapper Layout -->
            <header class="bg-white border-b border-gray-200 py-6 mb-6 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between w-full">
                    <!-- SECTION 1: Extreme Left (The Button) -->
                    <div class="flex-1 flex justify-start">
                        <!-- Modern Inertia Link component scales beautifully back onto your live datagrid canvas -->
                        <Link :href="route('courses.index')"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ (page_info && page_info.back_button) || 'Back To Courses' }}
                        </Link>
                    </div>

                    <!-- SECTION 2: Center (The Title) -->
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                            {{ (page_info && page_info.title) || 'Add New Course' }}
                        </h1>
                    </div>

                    <!-- SECTION 3: Right Spacer -->
                    <div class="flex-1"></div>
                </div>
            </header>

            <div class="bg-white shadow-sm sm:rounded-lg p-8 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Title -->
                        <div>
                            <label for='title' class="block text-base font-semibold text-gray-900 mb-3">Course Title</label>
                            <input type='text' name='title' id='title' v-model="form.title"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <span v-if="form.errors.title" class="text-red-700 text-sm block mt-2">{{ form.errors.title }}</span>
                        </div>

                        <!-- Course Description -->
                        <div>
                            <label for="description" class="block text-base font-semibold text-gray-900 mb-3">Course Description</label>
                            <textarea name="description" id="description" maxlength="500" v-model="form.description"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent h-20 resize-none"></textarea>
                            <span v-if="form.errors.description" class="text-red-700 text-sm block mt-2">{{ form.errors.description }}</span>
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
                            <input type="file" name="image_path" id='course_image_path' @change="handleImageUpload" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <span v-if="form.errors.image_path" class="text-red-700 text-sm block mt-2">{{ form.errors.image_path }}</span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex flex-col gap-4 pt-8 border-t border-gray-200 mt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link :href="route('courses.index')"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            Cancel
                        </Link>
                        <Button type="submit" :button_label="button_label" :processing="form.processing"/>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
