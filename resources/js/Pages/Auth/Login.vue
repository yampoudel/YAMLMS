<script setup>
import { computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';

// --- Props ---
const props = defineProps({
    status: { type: String, default: '' },
    canResetPassword: { type: Boolean, default: false },
});

// Initialize the global Inertia page instance store hook
const page = usePage();

// --- Form State ---
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// --- Form Submission ---
const submitForm = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: (errors) => {
            console.log('Login pipeline failure detected:', errors);
        },
    });
};

// --- UI Text Labels & Configurations ---
const statusMessage = computed(() => props.status || page.props.flash?.status || '');
const showResetLink = props.canResetPassword;
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="w-full max-w-md mx-auto">
            <!-- Status notification -->
            <div v-if="statusMessage" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ statusMessage }}
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
                            required
                            autofocus
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
                            autocomplete="current-password"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                        <span v-if="form.errors.password" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <div class="block">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                id="remember_me"
                                v-model="form.remember"
                                class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 mt-6">
                        <Link
                            v-if="showResetLink"
                            :href="route('password.request')"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Forgot your password?
                        </Link>

                        <Button type="submit" button_label="Log in" :processing="form.processing" />
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
