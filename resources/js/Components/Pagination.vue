<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array
});
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center mt-4 gap-1">
        <template v-for="(link, key) in links" :key="key">
            <!-- Render disabled state for missing URLs (e.g. inactive Prev/Next buttons) -->
            <div v-if="link.url === null"
                 class="px-4 py-2 text-sm text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed bg-gray-50"
                 v-html="link.label" />

            <!-- Render active single page links -->
            <Link v-else
                  :href="link.url"
                  class="px-4 py-2 text-sm border rounded-lg transition-colors duration-150"
                  :class="[
                      link.active
                      ? 'bg-blue-600 text-white border-blue-600 font-bold'
                      : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                  ]"
                  v-html="link.label" />
        </template>
    </div>
</template>
