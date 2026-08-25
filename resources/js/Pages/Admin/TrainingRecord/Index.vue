<script setup>
import { computed } from 'vue';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// --- Props ---
const props = defineProps({
    records: { type: [Object, Array], required: true },
});

// --- Computed List Setup ---
const recordList = computed(() => {
    const recordsArray = Array.isArray(props.records) ? props.records : (props.records.data ?? Object.values(props.records));

    return recordsArray.flatMap((record) => {
        if (record.user && record.course) {
            const progress = (record.user.course_progress ?? record.user.courseProgress ?? []).find((item) => item.course_id === record.course_id);
            const percent = Math.min(100, Math.max(0, Number(progress?.progress_percentage ?? 0)));

            return [
                {
                    id: record.id,
                    userId: record.user_id,
                    firstName: record.user.first_name,
                    lastName: record.user.last_name,
                    courseId: record.course_id,
                    courseTitle: record.course.title,
                    percent,
                },
            ];
        }

        return (record.enrolments ?? []).map((enrolment) => {
            const progress = (record.course_progress ?? record.courseProgress ?? []).find((item) => item.course_id === enrolment.course_id);
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

// Computed list properties
const totalRecords = computed(() => (Array.isArray(props.records) ? props.records.length : (props.records?.total ?? recordList.value.length ?? 0)));
const paginationLinks = computed(() => (Array.isArray(props.records?.links) ? props.records.links : []));
</script>

<template>
    <!-- Main Page Layout Wrapper -->
    <AuthenticatedLayout title="Training Records">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Page Header / Actions -->
            <div class="flex flex-col gap-4 px-6 py-5 border-b border-gray-200 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Training Records</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Monitor Learner's educational milestones, progress metrics, and certificate distribution paths.</p>
                </div>
            </div>

            <!-- Flash Notification -->
            <div class="px-6 pt-5">
                <FlashNotification />
            </div>

            <!-- Results Summary -->
            <div class="flex items-center justify-between px-6 py-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Total Training Records:
                    <span class="font-bold text-gray-900 dark:text-white">{{ totalRecords }}</span>
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
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Progress</th>
                                <th class="w-32 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Status</th>
                                <th class="w-48 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            <template v-if="recordList.length">
                                <tr v-for="(record, index) in recordList" :key="record.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ (props.records?.from ?? 1) + index }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ record.firstName }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ record.lastName }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ record.courseTitle }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center gap-3">
                                            <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                                <div class="h-full rounded-full bg-indigo-600" :style="{ width: `${record.percent}%` }"></div>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ record.percent }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm">
                                        <span
                                            :class="
                                                record.percent === 100
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                    : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400'
                                            "
                                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold">
                                            {{ record.percent === 100 ? 'Completed' : 'In Progress' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-medium">
                                        <a
                                            v-if="record.percent === 100"
                                            :href="`${route('certificates.download', record.courseId)}?user_id=${record.userId}`"
                                            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Download Certificate
                                        </a>
                                        <span v-else class="text-xs italic text-gray-400 dark:text-gray-600">--</span>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-sm font-medium text-gray-500 dark:text-gray-400">No records found.</td>
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
