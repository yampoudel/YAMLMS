<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';

// --- Form State ---
const form = useForm({
    password: '',
});

// --- Form Submission ---
const submitForm = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
        onError: (errors) => {
            console.log('Password confirmation failure detected:', errors);
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="w-full max-w-md mx-auto">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">This is a secure area of the application. Please confirm your password before continuing.</div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
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

                    <div class="flex items-center justify-end pt-4 border-t border-gray-200 mt-6">
                        <Button type="submit" button_label="Confirm" :processing="form.processing" />
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
