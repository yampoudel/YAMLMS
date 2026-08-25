<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

// --- Props ---
const props = defineProps({
    course: { type: [Object, Array], required: true },
    lessons: { type: [Object, Array], required: true },
    page_info: { type: [Object, Array], required: true },
    button_label: { type: String, required: true },
});

// --- Form State ---
const form = useForm({
    title: props.course?.title ?? '',
    description: props.course?.description ?? '',
    price: props.course?.price ?? '',
    status: props.course?.status ?? 'Active',
    image_path: undefined,
    _method: 'PUT',
});

// --- Form Submission ---
const submitForm = () => {
    const courseId = props.course?.id;
    if (!courseId) return;

    form.post(route('courses.update', { course: courseId }), {
        forceFormData: true,
        preserveScroll: true,
        onError: (errors) => {
            console.log('Validation pipeline failure detected:', errors);
        },
    });
};

// --- Image State & Validation ---
const imageError = ref('');
const previewImage = ref(props.course?.course_image_url ?? '');

const handleImagePreview = (e) => {
    imageError.value = '';
    const file = e.target.files?.[0];

    if (!file) {
        form.image_path = null;
        previewImage.value = props.course?.course_image_url ?? '';
        return;
    }

    if (!file.type.startsWith('image/')) {
        imageError.value = 'The file must be an image.';
        e.target.value = '';
        form.image_path = null;
        return;
    }

    const fileExtension = file.name.toLowerCase().split('.').pop();
    if (!['png', 'jpg', 'jpeg', 'webp'].includes(fileExtension)) {
        imageError.value = 'The image must be a file of type: png, jpg, jpeg, webp.';
        e.target.value = '';
        form.image_path = null;
        return;
    }

    if (file.size > 2048 * 1024) {
        imageError.value = 'The image must not be greater than 2MB.';
        e.target.value = '';
        form.image_path = null;
        previewImage.value = props.course?.course_image_url ?? '';
        return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
        previewImage.value = event.target?.result;
    };
    reader.readAsDataURL(file);
    form.image_path = file;
};

// UI text labels
const title = props.page_info?.title ?? 'Edit Course';
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
                            :href="route('users.index')"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ back_button }}
                        </Link>
                    </div>
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">{{ title }}</h1>
                    </div>
                    <div class="flex-1"></div>
                </div>
            </header>

            <!-- Main Form Card Container -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role Selector Input -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select id="role" v-model="form.role" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm">
                                <option value="Admin">Admin</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Learner">Learner</option>
                            </select>
                            <span v-if="form.errors.role" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.role }}</span>
                        </div>

                        <!-- Login Input -->
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Login</label>
                            <input
                                type="text"
                                id="login"
                                v-model="form.login"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.login" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.login }}</span>
                        </div>

                        <!-- First Name Input -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input
                                type="text"
                                id="first_name"
                                v-model="form.first_name"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.first_name" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.first_name }}</span>
                        </div>

                        <!-- Last Name Input -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input
                                type="text"
                                id="last_name"
                                v-model="form.last_name"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.last_name" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.last_name }}</span>
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                v-model="form.email"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.email" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.email }}</span>
                        </div>

                        <!-- Status Selector Input -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" v-model="form.status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm">
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                            <span v-if="form.errors.status" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.status }}</span>
                        </div>

                        <!-- Birth Date Input -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input
                                type="date"
                                id="birth_date"
                                v-model="form.birth_date"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.birth_date" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.birth_date }}</span>
                        </div>

                        <!-- Phone Number Input -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input
                                type="text"
                                id="phone"
                                v-model="form.phone"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.phone" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.phone }}</span>
                        </div>

                        <!-- Mobile Number Input -->
                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input
                                type="text"
                                id="mobile"
                                v-model="form.mobile"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.mobile" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.mobile }}</span>
                        </div>

                        <!-- Country Input -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input
                                type="text"
                                id="country"
                                v-model="form.country"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.country" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.country }}</span>
                        </div>

                        <!-- City Input -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input
                                type="text"
                                id="city"
                                v-model="form.city"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.city" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.city }}</span>
                        </div>

                        <!-- Suburb Input -->
                        <div>
                            <label for="suburb" class="block text-sm font-medium text-gray-700 mb-1">Suburb</label>
                            <input
                                type="text"
                                id="suburb"
                                v-model="form.suburb"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.suburb" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.suburb }}</span>
                        </div>

                        <!-- Postcode Input -->
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-gray-700 mb-1">Postcode</label>
                            <input
                                type="text"
                                id="postcode"
                                v-model="form.postcode"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.postcode" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.postcode }}</span>
                        </div>

                        <!-- Profile Image Input & Live Preview Block Frame -->
                        <div>
                            <label for="user_image_path" class="block text-base font-semibold text-gray-900 mb-3">Profile Image</label>
                            <input
                                type="file"
                                name="image_path"
                                id="user_image_path"
                                @change="handleImagePreview"
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                            <p v-if="form.errors.image_path || imageError" class="text-red-700 text-sm block mt-2 font-medium">{{ form.errors.image_path || imageError }}</p>
                            <div v-if="previewImage" class="mt-4">
                                <img :src="previewImage" alt="Preview" class="h-32 w-32 object-cover rounded-md border" />
                            </div>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex flex-col gap-4 pt-8 border-t border-gray-200 mt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link
                            :href="route('users.index')"
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
