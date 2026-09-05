<script setup>
import { ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

// -- Props --
defineProps({
    mustVerifyEmail: { type: Boolean, default: false },
    status: { type: String, default: '' },
});

// -- Input field references for focus control --
const firstNameInput = ref(null);
const lastNameInput = ref(null);
const emailInput = ref(null);

// -- Get the current user --
const currentUser = usePage().props.auth.user;

// -- Helpers to split full name into first and last name --
const getFirstName = () => {
    if (currentUser?.first_name) return currentUser.first_name;
    return currentUser?.name ? currentUser.name.split(' ')[0] : '';
};

const getLastName = () => {
    if (currentUser?.last_name) return currentUser.last_name;
    if (currentUser?.name) {
        const parts = currentUser.name.split(' ');
        if (parts.length <= 1) return '';
        parts.shift();
        return parts.join(' ');
    }
    return '';
};

// -- Set up initial form values --
const form = useForm({
    first_name: getFirstName(),
    last_name: getLastName(),
    email: currentUser?.email || '',
});

// -- Update profile information --
const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onError: (errors) => {
            if (errors.first_name) {
                firstNameInput.value?.focus();
            } else if (errors.last_name) {
                lastNameInput.value?.focus();
            } else if (errors.email) {
                emailInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <!-- Header -->
        <header class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Profile Information</h2>
            <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
        </header>

        <!-- Profile Form -->
        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        ref="firstNameInput"
                        v-model="form.first_name"
                        autofocus
                        autocomplete="given-name"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                    <span v-if="form.errors.first_name" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.first_name }}
                    </span>
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input
                        type="text"
                        id="last_name"
                        ref="lastNameInput"
                        v-model="form.last_name"
                        autocomplete="family-name"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                    <span v-if="form.errors.last_name" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.last_name }}
                    </span>
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        id="email"
                        ref="emailInput"
                        v-model="form.email"
                        autocomplete="username"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                    <span v-if="form.errors.email" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.email }}
                    </span>

                    <!-- Email Verification Notice -->
                    <div v-if="mustVerifyEmail && currentUser.email_verified_at === null" class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-sm text-yellow-800">
                            Your email address is unverified.
                            <Link :href="route('verification.send')" method="post" as="button" class="underline font-semibold ml-1 text-yellow-900 hover:text-black transition duration-150">
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-700">A new verification link has been sent to your email address.</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition duration-200 disabled:opacity-50">
                    Save Changes
                </button>

                <!-- Success Message -->
                <Transition enter-active-class="transition ease-in-out duration-150" enter-from-class="opacity-0" leave-active-class="transition ease-in-out duration-150" leave-to-class="opacity-0">
                    <span v-if="form.recentlySuccessful" class="text-sm font-semibold text-green-600">Saved successfully.</span>
                </Transition>
            </div>
        </form>
    </section>
</template>
