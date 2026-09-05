<script setup>
// -- Props --
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <!-- Error Message -->
    <div v-show="message">
        <p class="text-sm text-red-600 dark:text-red-400">
            {{ message }}
        </p>
    </div>
</template>
