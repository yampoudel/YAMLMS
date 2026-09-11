<script setup>
import { computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';

// --- Props ---
const props = defineProps({
    status: { type: String, default: '' },
});

// Initialize the global Inertia page instance store hook
const page = usePage();

// --- Form State ---
const form = useForm({
    email: '',
});

// --- Form Submission ---
const submitForm = () => {
    form.post(route('password.email'), {
        onError: (errors) => {
            console.log('Password reset request failure detected:', errors);
        },
    });
};

// --- UI Text Labels & Configurations ---
const statusMessage = computed(() => props.status || page.props.flash?.status || '');
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="w-full max-w-md mx-auto">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>

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
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                        <span v-if="form.errors.email" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-200 mt-6">
                        <Button type="submit" button_label="Email Password Reset Link" :processing="form.processing" />
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
