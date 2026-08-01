<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

const props = defineProps({
    page_info: Object,
    button_label: {
        type: String,
        required: true
    },
    user: Object,
});

const pageInfo = props.page_info ?? {};
const previewImage = ref(props.user?.image_path_url ?? '');
const imageError = ref('');

const parseDateForInput = (value) => {
    if (!value) return null;
    if (typeof value === 'string') {
        const match = value.match(/^(\d{4}-\d{2}-\d{2})/);
        if (match) return match[1];
    }
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return null;
    }
    return date.toISOString().slice(0, 10);
};

const form = useForm({
    role: props.user?.role ?? '',
    login: props.user?.login ?? '',
    first_name: props.user?.first_name ?? '',
    last_name: props.user?.last_name ?? '',
    email: props.user?.email ?? '',
    status: props.user?.status ?? '',
    birth_date: parseDateForInput(props.user?.birth_date ?? null),
    phone: props.user?.phone ?? '',
    mobile: props.user?.mobile ?? '',
    country: props.user?.country ?? '',
    city: props.user?.city ?? '',
    postcode: props.user?.postcode ?? '',
    suburb: props.user?.suburb ?? '',
    image_path: undefined,
});

const submit = () => {
    const userId = props.user?.id;
    if (!userId) return;
    form.put(`/users/${userId}`);
};

const handleImagePreview = (e) => {
    imageError.value = '';
    const file = e.target.files?.[0];

    if (!file) {
        // no new file selected; leave image_path undefined so existing image is preserved
        form.image_path = null;
        previewImage.value = props.user?.image_path_url ?? '';
        return;
    }

    if (!file.type.startsWith('image/')) {
        imageError.value = 'The file must be an image.';
        e.target.value = '';
        form.image_path = null;
        return;
    }

    const fileName = file.name.toLowerCase();
    const allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
    const fileExtension = fileName.split('.').pop();

    if (!allowedExtensions.includes(fileExtension)) {
        imageError.value = 'The image must be a file of type: png, jpg, jpeg, webp.';
        e.target.value = '';
        form.image_path = null;
        return;
    }

    const maxSizeInBytes = 2048 * 1024;
    if (file.size > maxSizeInBytes) {
        imageError.value = 'The image must not be greater than 2MB.';
        e.target.value = '';
        form.image_path = null;
        previewImage.value = props.user?.image_path_url ?? '';
        return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
        previewImage.value = event.target?.result;
    };
    reader.readAsDataURL(file);
    form.image_path = file;
};

const back_button = pageInfo.back_button ?? 'Back To Users';
const title = pageInfo.title ?? 'Edit User';
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <header class="bg-white border-b border-gray-200 py-6 mb-6 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between w-full">
                    <!-- SECTION 1: Extreme Left (The Button) -->
                    <div class="flex-1 flex justify-start">
                        <Link href="/users"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <!-- SVG Icon makes the "Back" action clear -->
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ back_button }}
                        </Link>
                    </div>

                    <!-- SECTION 2: Center (The Title) -->
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                            {{ title }}
                        </h1>
                    </div>

                    <!-- SECTION 3: Right Spacer (Keeps the title perfectly in the middle) -->
                    <div class="flex-1"></div>
                </div>
            </header>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select id="role" name="role" required v-model="form.role"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm">
                                <option value="Admin">Admin</option>
                                <option value="Learner">Learner</option>
                                <option value="Teacher">Teacher</option>
                            </select>
                            <span v-if="form.errors.role" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.role }}</span>
                        </div>

                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Login</label>
                            <input id="login" name="login" type="text" v-model="form.login"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.login" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.login }}</span>
                        </div>

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input id="first_name" name="first_name" type="text" v-model="form.first_name"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.first_name" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.first_name }}</span>
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input id="last_name" name="last_name" type="text" v-model="form.last_name"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.last_name" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.last_name }}</span>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" name="email" type="email" v-model="form.email"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.email" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.email }}</span>
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input id="birth_date" name="birth_date" type="date" v-model="form.birth_date"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.birth_date" class="js-birthdate-error text-red-700 text-sm block mt-1 font-medium">{{ form.errors.birth_date }}</span>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input id="phone" name="phone" type="text" v-model="form.phone"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.phone" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.phone }}</span>
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                            <input id="mobile" name="mobile" type="text" v-model="form.mobile"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.mobile" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.mobile }}</span>
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input id="country" name="country" type="text" v-model="form.country"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.country" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.country }}</span>
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input id="city" name="city" type="text" v-model="form.city"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.city" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.city }}</span>
                        </div>

                        <div>
                            <label for="postcode" class="block text-sm font-medium text-gray-700 mb-1">Postcode</label>
                            <input id="postcode" name="postcode" type="text" v-model="form.postcode"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.postcode" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.postcode }}</span>
                        </div>

                        <div>
                            <label for="suburb" class="block text-sm font-medium text-gray-700 mb-1">Suburb</label>
                            <input id="suburb" name="suburb" type="text" v-model="form.suburb"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.suburb" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.suburb }}</span>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" required v-model="form.status"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm">
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                            <span v-if="form.errors.status" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.status }}</span>
                        </div>

                        <div class="md:col-span-2">
                            <label for="image_path" class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                            <input id="image_path" name="image_path" type="file" accept="image/*"
                                class="w-full border border-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 text-sm text-gray-500"
                                @change="handleImagePreview">
                            <span v-if="form.errors.image_path" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.image_path }}</span>
                            <p v-if="imageError" class="text-red-700 text-sm block mt-1 font-medium">{{ imageError }}</p>
                            <div v-if="previewImage" class="mt-4">
                                <img :src="previewImage" alt="Preview" class="h-32 w-32 object-cover rounded-md border" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                        <Link href="/users"
                            class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm hover:bg-gray-50 transition duration-200">
                            Cancel
                        </Link>

                        <div class="flex items-center">
                            <Button :button_label="button_label" :processing="form.processing" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-full shadow-sm transition duration-200" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
