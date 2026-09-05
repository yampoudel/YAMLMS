<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';

// -- Props --
defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const title = 'Account Settings';
const back_button = 'Back To Dashboard';
</script>

<template>
    <Head title="Account Settings" />

    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 py-6 mb-6 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between w-full">
                    <!-- Back Button -->
                    <div class="flex-1 flex justify-start">
                        <Link
                            :href="route('dashboard')"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ back_button }}
                        </Link>
                    </div>

                    <!-- Page Title -->
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                            {{ title }}
                        </h1>
                    </div>
                    <div class="flex-1"></div>
                </div>
            </header>

            <!-- Main Content Area -->
            <div class="space-y-6">
                <!-- Profile Information -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="max-w-2xl">
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                    </div>
                </div>

                <!-- Update Password -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="max-w-2xl">
                        <UpdatePasswordForm />
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-red-100 bg-gradient-to-r from-white to-red-50/10">
                    <div class="max-w-2xl">
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
