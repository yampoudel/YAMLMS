<script setup>
import { ref, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Modal from '@/Components/Modal.vue';

// -- Input field references for focus control --
const passwordInput = ref(null);
const confirmingUserDeletion = ref(false);

// -- Initialize form data --
const form = useForm({ password: '' });

// -- Open deletion modal and focus input --
const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

// -- Submit account deletion request --
const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
    });
};

// -- Close modal and reset form values --
const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section>
        <!-- Header -->
        <header class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Delete Account</h2>
            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </p>
        </header>

        <!-- Trigger Button -->
        <button
            type="button"
            @click="confirmUserDeletion"
            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition duration-200">
            Delete Account
        </button>

        <!-- Deletion Confirmation Modal -->
        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <form @submit.prevent="deleteUser" class="p-6 space-y-6">
                <!-- Modal Warning Content -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Are you sure you want to delete your account?</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your
                        account.
                    </p>
                </div>

                <!-- Password Input -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input
                            id="delete_password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Enter your account password"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm" />

                        <span v-if="form.errors.password" class="text-red-700 text-sm block mt-1 font-medium">
                            {{ form.errors.password }}
                        </span>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col gap-4 pt-4 border-t border-gray-100 sm:flex-row sm:items-center sm:justify-end">
                    <button
                        type="button"
                        @click="closeModal"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition duration-200 disabled:opacity-50">
                        Confirm Account Deletion
                    </button>
                </div>
            </form>
        </Modal>
    </section>
</template>
