<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// --- Props ---
const props = defineProps({
    enrolments: { type: [Object, Array], required: true },
});

// --- Actions ---
const deleteEnrolment = (id) => {
    if (confirm('Are you sure you want to delete this enrolment?')) {
        router.delete(route('enrolments.destroy', id), {
            preserveState: true,
            replace: true,
        });
    }
};

// --- Formatters ---
const formatDate = (value) => {
    if (!value) return '';

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';

    return new Intl.DateTimeFormat('en-AU').format(date);
};

// Computed list properties
const enrolmentList = computed(() => (Array.isArray(props.enrolments) ? props.enrolments : (props.enrolments?.data ?? [])));
const totalEnrolments = computed(() => props.enrolments?.total ?? enrolmentList.value.length ?? 0);
const paginationLinks = computed(() => (Array.isArray(props.enrolments?.links) ? props.enrolments.links : []));
</script>

<template>
    <!-- Main Page Layout Wrapper -->
    <AuthenticatedLayout title="Enrolments">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Page Header / Actions -->
            <div class="flex flex-col gap-4 px-6 py-5 border-b border-gray-200 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Enrolments</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View comprehensive lists of users enrolled in active courses and manage record deletion privileges.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Navigation / Redirect Button -->
                    <Link
                        :href="route('users.index')"
                        class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200">
                        Go To Users
                    </Link>
                </div>
            </div>

            <!-- Flash Notification -->
            <div class="px-6 pt-5">
                <FlashNotification />
            </div>

            <!-- Results Summary -->
            <div class="flex items-center justify-between px-6 py-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Total Enrolments:
                    <span class="font-bold text-gray-900 dark:text-white">{{ totalEnrolments }}</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="px-6 pb-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full min-w-[1000px] divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="w-16 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">S.N.</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">First Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Last Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Course Title</th>
                                <th class="w-48 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Enrolled Date</th>
                                <th class="w-32 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            <template v-if="enrolmentList.length">
                                <tr v-for="(enrolment, index) in enrolmentList" :key="enrolment.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ (props.enrolments?.from ?? 1) + index }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ enrolment.user?.first_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ enrolment.user?.last_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ enrolment.course?.title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ formatDate(enrolment.enrolled_at) }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-medium">
                                        <button
                                            type="button"
                                            @click="deleteEnrolment(enrolment.id)"
                                            class="font-semibold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:underline cursor-pointer">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-sm font-medium text-gray-500 dark:text-gray-400">No records found.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table Pagination Footer -->
            <div class="px-6 pb-6">
                <Pagination v-if="paginationLinks.length" :links="paginationLinks" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
