<script setup>
import { onMounted, ref } from 'vue';

// -- Model --
const model = defineModel({ type: String, required: true });

// -- Props --
defineProps({
    disabled: { type: Boolean, default: false },
});

// -- Input field references for focus control --
const input = ref(null);

// -- Auto-focus on mount if attribute is present --
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

// -- Expose methods --
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <!-- Text Input -->
    <input
        ref="input"
        v-model="model"
        :disabled="disabled"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
</template>
