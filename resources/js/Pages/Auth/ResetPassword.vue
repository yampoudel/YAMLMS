<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';

// --- Props ---
const props = defineProps({
    email: { type: String, default: '' },
    token: { type: String, required: true },
});

// --- Form State ---
const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

// --- Form Submission ---
const submitForm = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: (errors) => {
            console.log('Password updates pipeline failure detected:', errors);
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="w-full space-y-6">
            <form @submit.prevent="submitForm" class="space-y-6 w-full block">
                <div class="w-full block">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full block border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 text-sm box-border" />
                    <span v-if="form.errors.email" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.email }}
                    </span>
                </div>

                <div class="w-full block">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        class="w-full block border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 text-sm box-border" />
                    <span v-if="form.errors.password" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.password }}
                    </span>
                </div>

                <div class="w-full block">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full block border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 text-sm box-border" />
                    <span v-if="form.errors.password_confirmation" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.password_confirmation }}
                    </span>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 mt-6 w-full">
                    <Button type="submit" button_label="Reset Password" :processing="form.processing" />
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
