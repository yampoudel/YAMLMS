<script setup>
import { computed } from 'vue';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    records: { type: [Object, Array], required: true },
});

const recordList = computed(() => {
    // Ensure props.records is treated as an array
    const recordsArray = Array.isArray(props.records)
        ? props.records
        : (props.records.data ?? Object.values(props.records));

    return recordsArray.flatMap((record) => {
        if (record.user && record.course) {
            const progress = (record.user.course_progress ?? record.user.courseProgress ?? [])
                .find((item) => item.course_id === record.course_id);
            const percent = Math.min(100, Math.max(0, Number(progress?.progress_percentage ?? 0)));

            return [{
                id: record.id,
                userId: record.user_id,
                firstName: record.user.first_name,
                lastName: record.user.last_name,
                courseId: record.course_id,
                courseTitle: record.course.title,
                percent,
            }];
        }

        return (record.enrolments ?? []).map((enrolment) => {
            const progress = (record.course_progress ?? record.courseProgress ?? [])
                .find((item) => item.course_id === enrolment.course_id);
            const percent = Math.min(100, Math.max(0, Number(progress?.progress_percentage ?? 0)));

            return {
                id: `${record.id}-${enrolment.id}`,
                userId: record.id,
                firstName: record.first_name,
                lastName: record.last_name,
                courseId: enrolment.course_id,
                courseTitle: enrolment.course?.title ?? 'Unknown course',
                percent,
            };
        });
    });
});

// Compute the total number of records and pagination links
const totalRecords = computed(() =>
    Array.isArray(props.records) ? props.records.length : (props.records?.total ?? recordList.value.length ?? 0)
);

const paginationLinks = computed(() =>
    Array.isArray(props.records) ? [] : (props.records?.links ?? [])
);

const rowNumber = (index) => {
    const currentPage = props.records?.current_page ?? 1;
    const perPage = props.records?.per_page ?? recordList.value.length;

    return ((currentPage - 1) * perPage) + index + 1;
};
</script>

<template>
    <AuthenticatedLayout title="Training Records">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200">
            <div class="w-full text-gray-600 pl-2 text-sm mb-4">
                Total Training Records: <span class="font-bold text-gray-900">{{ totalRecords }}</span>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                S.N.
                            </th>

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                First Name
                            </th>

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Last Name
                            </th>

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Course Title
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Progress
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="(record, index) in recordList" :key="record.id"
                            class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ rowNumber(index) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ record.firstName }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ record.lastName }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ record.courseTitle }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full bg-indigo-600"
                                            :style="{ width: `${record.percent}%` }">
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">{{ record.percent }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span
                                    :class="record.percent === 100 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'"
                                    class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold">
                                    {{ record.percent === 100 ? 'Completed' : 'In Progress' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <a v-if="record.percent === 100"
                                    :href="`${route('certificates.download', record.courseId)}?user_id=${record.userId}`"
                                    class="text-xs font-black text-indigo-600 hover:underline">
                                    Download Certificate
                                </a>
                                <span v-else class="text-xs italic text-slate-300">--</span>
                            </td>
                        </tr>
                        <tr v-if="!recordList.length">
                            <td colspan="7" class="text-center py-10 text-gray-500 text-sm font-medium">
                                No records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination v-if="paginationLinks.length" :links="paginationLinks" />
        </div>
    </AuthenticatedLayout>
</template>
