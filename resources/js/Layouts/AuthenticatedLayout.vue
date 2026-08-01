<script setup>
import Navigation from '@/Layouts/Partials/Navigation.vue';
import Sidebar from '@/Layouts/Partials/Sidebar.vue';

// Accept an optional page header title string prop
defineProps({
    title: {
        type: String,
        default: ''
    }
});
</script>

<template>
    <!-- Main backdrop box framework - mirrors  Blade HTML structural body perfectly -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col">

        <!-- Include fresh Vue Navigation component fragment -->
        <Navigation />

        <div class="flex w-full flex-1 overflow-hidden">
            <!-- Include fresh Vue Sidebar navigation panel fragment -->
            <Sidebar />

            <!-- Content Area Right Side -->
            <div class="flex-1 overflow-auto">

                <!-- Page Heading — Replaces Blade @isset($header) template section check -->
                <header v-if="title" class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-full py-6 px-4">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white leading-tight">
                            {{ title }}
                        </h1>
                    </div>
                </header>

                <!-- Page Content — Replaces legacy Blade {{ $slot }} tag mapping -->
                <main class="p-6">
                    <!-- This slot directive dynamically injects view file (e.g. Index.vue or Create.vue) -->
                    <slot />
                </main>

            </div>
        </div>
    </div>
</template>
