<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Update Password</h2>
            <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        autocomplete="current-password"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />
                    <span v-if="form.errors.current_password" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.current_password }}
                    </span>
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />

                    <!-- ONLY show password errors here if it is NOT a confirmation mismatch -->
                    <span v-if="form.errors.password && !form.errors.password.includes('confirmation')" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.password }}
                    </span>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />

                    <!-- SHOW the error here if the confirmation field fails on its own OR if the password error mentions "confirmation" -->
                    <span v-if="form.errors.password_confirmation || (form.errors.password && form.errors.password.includes('confirmation'))" class="text-red-700 text-sm block mt-1 font-medium">
                        {{ form.errors.password_confirmation || form.errors.password }}
                    </span>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition duration-200 disabled:opacity-50">
                    Save Password
                </button>

                <!-- FIXED: Implemented structural v-if container blocks inside the transition engine -->
                <Transition enter-active-class="transition ease-in-out duration-150" enter-from-class="opacity-0" leave-active-class="transition ease-in-out duration-150" leave-to-class="opacity-0">
                    <span v-if="form.recentlySuccessful" class="text-sm font-semibold text-green-600">Saved successfully.</span>
                </Transition>
            </div>
        </form>
    </section>
</template>
