<script setup>
// Available globally as a compiler macro - no manual imports needed
defineProps({
    button_label: {
        type: String,
        required: true,
    },
    // Triggers loading protection states seamlessly from Inertia forms
    processing: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
  <button
    type="submit"
    :disabled="processing"
    class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-full shadow-sm hover:bg-indigo-700 transition duration-200 disabled:opacity-50 cursor-pointer"
  >
    {{ button_label }}
  </button>
</template>
