<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <!-- Centered container for the authentication card -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- App Logo Header Area -->
        <div>
            <Link :href="route('login')">
                <ApplicationLogo />
            </Link>
        </div>

        <!-- Form Layout Wrapper -->
        <main class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <!-- Dynamically injects the current page component (e.g., Login.vue) -->
            <slot />
        </main>
    </div>
</template>
