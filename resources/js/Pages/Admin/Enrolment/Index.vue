<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashNotification from '@/Components/FlashNotification.vue';

const props = defineProps({
    enrolments: { type: [Object, Array], required: true },
});

const enrolmentList = computed(() => {
    if (Array.isArray(props.enrolments)) {
        return props.enrolments;
    }

    return props.enrolments?.data ?? [];
});

const totalEnrolments = computed(() => {
    if (Array.isArray(props.enrolments)) {
        return props.enrolments.length;
    }

    return props.enrolments?.total || enrolmentList.value.length || 0;
});

const paginationLinks = computed(() => props.enrolments?.links ?? []);

const formatDate = (value) => {
    if (!value) return '';

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';

    return new Intl.DateTimeFormat('en-AU').format(date);
};

const deleteEnrolment = (enrolment) => {
    if (confirm('Are you sure you want to delete this enrolment?')) {
        router.delete(route('enrolments.destroy', enrolment.id), {
            preserveState: true,
            replace: true,
        });
    }
};
</script>

<template>
    <AuthenticatedLayout title="Enrolments">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
            <div class="mb-6 flex w-full items-center justify-between border-b border-gray-200 pb-4">
                <div class="flex gap-4">
                    <Link
                        :href="route('users.index')"
                        class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-blue-700"
                    >
                        Go To Users
                    </Link>
                </div>
            </div>

            <FlashNotification />

            <div class="mb-4 w-full pl-2 text-sm text-gray-600">
                Total Enrolments: <span class="font-bold text-gray-900">{{ totalEnrolments }}</span>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">S.N.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">First Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Last Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Course Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Enrolled Date</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="(enrolment, index) in enrolmentList" :key="enrolment.id" class="transition-colors duration-150 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ (props.enrolments?.from || 1) + index }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ enrolment.user?.first_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ enrolment.user?.last_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ enrolment.course?.title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ formatDate(enrolment.enrolled_at) }}</td>
                            <td class="px-4 py-3 text-center text-sm">
                                <button
                                    type="button"
                                    class="font-semibold text-red-600 hover:underline"
                                    @click="deleteEnrolment(enrolment)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!enrolmentList.length">
                            <td colspan="6" class="py-10 text-center text-sm font-medium text-gray-500">No records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination v-if="paginationLinks.length" :links="paginationLinks" />
        </div>
    </AuthenticatedLayout>
</template>
