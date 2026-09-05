<script setup>
// -- Props --
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <!-- Form Label -->
    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
