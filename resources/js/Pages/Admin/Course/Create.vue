<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

// --- Props ---
const props = defineProps({
    page_info: { type: [Object, Array], required: true },
    button_label: { type: String, required: true },
});

// --- Form State ---
const form = useForm({
    title: '',
    description: '',
    price: '',
    status: 'Active',
    image_path: null,
});

// --- Image State & Preview Handling ---
const previewImage = ref('');

const handleImageUpload = (event) => {
    const file = event.target.files?.[0]; // Extracted only the first file object

    if (!file) {
        form.image_path = null;
        previewImage.value = '';
        return;
    }

    // Convert binary file safely inside JavaScript memory
    previewImage.value = window.URL.createObjectURL(file);
    form.image_path = file;
};

// --- Form Submission ---
const submitForm = () => {
    form.post(route('courses.store'), {
        forceFormData: true,
        preserveScroll: true,
        onError: (errors) => {
            console.log('Validation pipeline failure detected:', errors);
        },
    });
};

// UI text labels
const title = props.page_info?.title ?? 'Add New Course';
const back_button = props.page_info?.back_button ?? 'Back To Courses';
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Header Section Bar -->
            <header class="bg-white border-b border-gray-200 py-6 mb-6 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between w-full">
                    <div class="flex-1 flex justify-start">
                        <Link
                            :href="route('courses.index')"
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
                        <!-- Course Title Input -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.title" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.title }}
                            </span>
                        </div>

                        <!-- Description Input -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input
                                type="text"
                                id="description"
                                v-model="form.description"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.description" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.description }}
                            </span>
                        </div>

                        <!-- Price Input -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (AUD)</label>
                            <input
                                type="number"
                                step="0.01"
                                id="price"
                                v-model="form.price"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.price" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.price }}
                            </span>
                        </div>

                        <!-- Status Selector Input -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" v-model="form.status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm">
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                            <span v-if="form.errors.status" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.status }}
                            </span>
                        </div>

                        <!-- Course Image Input & Preview -->
                        <div class="md:col-span-2">
                            <label for="course_image_path" class="block text-base font-semibold text-gray-900 mb-3">Course Image</label>
                            <input
                                type="file"
                                name="image_path"
                                id="course_image_path"
                                @change="handleImageUpload"
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                            <p v-if="form.errors.image_path" class="text-red-700 text-sm block mt-2 font-medium">
                                {{ form.errors.image_path }}
                            </p>
                            <div v-if="previewImage" class="mt-4">
                                <img :src="previewImage" alt="Preview" class="h-32 w-32 object-cover rounded-md border" />
                            </div>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex flex-col gap-4 pt-8 border-t border-gray-200 mt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link
                            :href="route('courses.index')"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            Cancel
                        </Link>
                        <Button :button_label="button_label" :processing="form.processing" type="submit" />
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
