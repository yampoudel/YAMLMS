<script setup>
import Navigation from '@/Layouts/Partials/Navigation.vue';
import Sidebar from '@/Layouts/Partials/Sidebar.vue';
import Header from '@/Layouts/Partials/Header.vue';

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

            <Header :title="title" />

            <!-- Page Content — Replaces legacy Blade {{ $slot }} tag mapping -->
            <main class="p-6">
                <!-- This slot directive dynamically injects view file (e.g. Index.vue or Create.vue) -->
                <slot />
            </main>

            </div>
        </div>
    </div>
</template>
