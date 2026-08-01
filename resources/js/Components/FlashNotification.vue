<script setup>
import {usePage} from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';

const page = usePage();
const flash = computed(()=> page.props.flash || {success: null, error:null});

// Control the visibility of the notification banner
const showNotification = ref(true);

// Automatically hide the notification after 3 seconds
onMounted(() => {
    if (flash.value.success ||flash.value.error) {
        showNotification.value = true;
        setTimeout(() => {
            showNotification.value = false;
        }, 3000);
    };
});

watch(flash, (newFlash) => {
    if (newFlash.success || newFlash.error) {
        showNotification.value = true;
        setTimeout(() => {
            showNotification.value = false;
        }, 3000);
    }
});

</script>
<template>
    <!-- Session Status Alert Banner Notifications with Native Vue Animation -->
    <Transition name="fade">
        <div v-if="showNotification && (flash?.success || flash?.error)"
            :class="['mb-4 p-3 border rounded text-sm font-medium shadow-xs', flash.success ? 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800' : 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800']">
            {{ flash.success || flash.error }}
        </div>
    </Transition>
</template>

<style scoped>
/* Triggers the 0.8-second opacity fading transition math curve */
.fade-leave-active {
  transition: opacity 0.8s ease;
}

/* Defines the destination state (animating smoothly down to invisible) */
.fade-leave-to {
  opacity: 0;
}
</style>
