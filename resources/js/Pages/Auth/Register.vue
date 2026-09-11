<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';

// --- Form State ---
const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// --- Form Submission ---
const submitForm = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: (errors) => {
            console.log('Registration pipeline failure detected:', errors);
        },
    });
};
</script>

<template>
    <GuestLayout>
        <div class="w-full max-w-md mx-auto">
            <!-- Form Card Wrapper -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            v-model="form.first_name"
                            required
                            autofocus
                            autocomplete="first_name"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                        <span v-if="form.errors.first_name" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.first_name }}
                        </span>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            v-model="form.last_name"
                            required
                            autocomplete="last_name"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                        <span v-if="form.errors.last_name" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.last_name }}
                        </span>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                        <span v-if="form.errors.email" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input
                            type="password"
                            id="password"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                        <span v-if="form.errors.password" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                        <span v-if="form.errors.password_confirmation" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.password_confirmation }}
                        </span>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 mt-6">
                        <Link
                            :href="route('login')"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Already registered?
                        </Link>

                        <Button type="submit" button_label="Register" :processing="form.processing" />
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
