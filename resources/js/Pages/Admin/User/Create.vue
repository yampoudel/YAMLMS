<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

// Ingest ALL properties from controller, including  dynamic button label
const props = defineProps({
    page_info: { type: Object, required: true },
    button_label: { type: String, required: true },
});

// Initialize Inertia's form tracking matrix
const form = useForm({
    role: 'Admin',
    login: '',
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    status: 'Active',
    birth_date: '',
    phone: '',
    mobile: '',
    country: '',
    city: '',
    postcode: '',
    suburb: '',
    image_path: null
});

// Monitor physical input field alterations and map files safely into state memory
const handleImageUpload = (event) => {
    form.image_path = event.target.files[0];
};

// Fire structured inputs straight through the single-page application routing engine
const submitForm = () => {
    form.post('/users', {
        preserveScroll: true,
        onError: (errors) => {
            console.log('Validation pipeline failure detected:', errors);
        }
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
                        <Link href="/users"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ page_info?.back_button || 'Back To Users' }}
                        </Link>
                    </div>

                    <!-- SECTION 2: Center (The Title) -->
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                            {{ page_info?.title || 'Add New User' }}
                        </h1>
                    </div>

                    <!-- SECTION 3: Right Spacer -->
                    <div class="flex-1"></div>
                </div>
            </header>

            <!-- Main Form Panel Presentation Container Layout Box -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Role Selector Input -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select id="role" v-model="form.role"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm">
                                <option value="Admin">Admin</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Learner">Learner</option>
                            </select>
                            <span v-if="form.errors.role" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.role }}</span>
                        </div>

                        <!-- Login Input -->
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Login</label>
                            <input type="text" id="login" v-model="form.login"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.login" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.login }}</span>
                        </div>

                        <!-- First Name Input -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" id="first_name" v-model="form.first_name"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.first_name" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.first_name }}</span>
                        </div>

                        <!-- Last Name Input -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" id="last_name" v-model="form.last_name"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.last_name" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.last_name }}</span>
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" v-model="form.email"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.email" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.email }}</span>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="password" v-model="form.password"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.password" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.password }}</span>
                        </div>
                        <!-- Status Selector Input -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" v-model="form.status"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm">
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                            <span v-if="form.errors.status" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.status }}</span>
                        </div>

                        <!-- Birth Date Input -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" id="birth_date" v-model="form.birth_date"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.birth_date" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.birth_date }}</span>
                        </div>

                        <!-- Contact Parameters Block Extensions (Synchronised to match your database schema properties) -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" id="phone" v-model="form.phone" class="w-full border-gray-300 rounded-md shadow-sm p-2 border text-sm focus:ring focus:ring-indigo-200" />
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input type="text" id="mobile" v-model="form.mobile" class="w-full border-gray-300 rounded-md shadow-sm p-2 border text-sm focus:ring focus:ring-indigo-200" />
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" id="country" v-model="form.country" class="w-full border-gray-300 rounded-md shadow-sm p-2 border text-sm focus:ring focus:ring-indigo-200" />
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" id="city" v-model="form.city" class="w-full border-gray-300 rounded-md shadow-sm p-2 border text-sm focus:ring focus:ring-indigo-200" />
                        </div>

                        <!-- Postcode -->
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-gray-700 mb-1">Post Code</label>
                            <input type="text" id="postcode" v-model="form.postcode"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.postcode" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.postcode }}</span>
                        </div>

                        <!-- Suburb -->
                        <div>
                            <label for="suburb" class="block text-sm font-medium text-gray-700 mb-1">Suburb</label>
                            <input type="text" id="suburb" v-model="form.suburb"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                            <span v-if="form.errors.suburb" class="text-red-700 text-sm block mt-1 font-medium">{{ form.errors.suburb }}</span>
                        </div>

                        <!-- Profile Image -->
                        <div>
                            <label for="profile_image" class="block text-base font-semibold text-gray-900 mb-3">Profile Image</label>
                            <input type="file" name="image_path" id="profile_image" @change="handleImageUpload" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <span v-if="form.errors.image_path" class="text-red-700 text-sm block mt-2">{{ form.errors.image_path }}</span>
                        </div>

                    </div>

                    <!-- Submit Control Box Panel Row -->
                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                        <Link href="/users"
                            class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm hover:bg-gray-50 transition duration-200">
                            Cancel
                        </Link>

                        <Button :button_label="button_label" :processing="form.processing"/>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
