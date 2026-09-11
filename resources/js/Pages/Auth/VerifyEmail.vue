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

// --- Form States ---
const verificationForm = useForm({});
const logoutForm = useForm({});

// --- Form Submissions ---
const submitVerification = () => {
    verificationForm.post(route('verification.send'), {
        onError: (errors) => {
            console.log('Verification resend pipeline failure:', errors);
        },
    });
};

const submitLogout = () => {
    logoutForm.post(route('logout'), {
        onError: (errors) => {
            console.log('Logout pipeline failure:', errors);
        },
    });
};

// --- UI Text Labels & computed properties ---
const statusMessage = computed(() => props.status || page.props.flash?.status || '');
const verificationLinkSent = computed(() => statusMessage.value === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="w-full max-w-md mx-auto">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send
                you another.
            </div>

            <!-- Success notification -->
            <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                A new verification link has been sent to the email address you provided during registration.
            </div>

            <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-200 mt-6">
                <form @submit.prevent="submitVerification">
                    <Button type="submit" button_label="Resend Verification Email" :processing="verificationForm.processing" />
                </form>

                <form @submit.prevent="submitLogout">
                    <button
                        type="submit"
                        :disabled="logoutForm.processing"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 cursor-pointer disabled:opacity-50">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
