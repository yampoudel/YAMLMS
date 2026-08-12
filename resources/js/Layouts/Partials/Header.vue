<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true,
    }
});
</script>

<template>
    <!-- Page Heading — Replaces Blade @isset($header) template section check -->
   <header v-if="title" class="w-full bg-white dark:bg-gray-800 shadow">
        <div class="flex flex-col w-full px-6 py-4">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white leading-tight text-center">
                {{ title }}
            </h1>
        </div>
    </header>
</template>
