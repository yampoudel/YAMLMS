<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminView from './Partials/AdminView.vue';
import TeacherView from './Partials/TeacherView.vue';

// --- Props ---
const props = defineProps({
    data: { type: [Object, Array], required: true },
});

// --- Context & State ---
const page = usePage();

// --- Computed list properties ---
const user = computed(() => page.props.auth?.user);
const isAdmin = computed(() => user.value?.role === 'Admin');
const data = computed(() => (Array.isArray(props.data) ? props.data : props.data ? props.data : []));
</script>

<template>
    <AuthenticatedLayout title="Overview Dashboard">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <AdminView v-if="isAdmin" :data="data" />
            <TeacherView v-else :data="data" />
        </div>
    </AuthenticatedLayout>
</template>
