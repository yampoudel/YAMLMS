<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';

// Instantiate reactive template overlay menu toggle parameters
const isMobileOpen = ref(false);
const isDropdownOpen = ref(false);

// Safely read global authenticated properties from shared backend props
const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'User', email: '' });

// Expose a safe path evaluation state directly to the template block
const currentPath = computed(() => window.location.pathname);

// Programmatic session exit handler
const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 w-full z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')" class="text-4xl font-black text-gray-800 dark:text-gray-200 tracking-tighter">YAMLMS</Link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <Link
                            :href="route('dashboard')"
                            :class="[
                                currentPath === '/dashboard'
                                    ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700',
                            ]"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out">
                            Dashboard
                        </Link>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6 relative">
                    <button
                        @click="isDropdownOpen = !isDropdownOpen"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 cursor-pointer">
                        <div>{{ user.name }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://w3.org" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div v-if="isDropdownOpen" class="absolute right-0 top-12 z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 py-1">
                        <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition">Profile</Link>
                        <button
                            @click="logout"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600 transition cursor-pointer font-medium">
                            Log Out
                        </button>
                    </div>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        @click="isMobileOpen = !isMobileOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out cursor-pointer">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path v-if="!isMobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div v-if="isMobileOpen" class="sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="pt-2 pb-3 space-y-1">
                <Link
                    href="/dashboard"
                    class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    Dashboard
                </Link>
            </div>
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ user.name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ user.email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <Link
                        href="/profile"
                        class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        Profile
                    </Link>
                    <button
                        @click="logout"
                        class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out cursor-pointer">
                        Log Out
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>
