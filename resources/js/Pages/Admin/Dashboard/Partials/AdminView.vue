<script setup>
import { computed } from 'vue';

// --- Props ---
const props = defineProps({
    data: {
        type: Object,
        required: true,
        default: () => ({
            total_users: 0,
            total_courses: 0,
            total_enrolments: 0,
            recent_users: [],
        }),
    },
});

// --- Date Formatter Helper ---
const formatDate = (value) => {
    if (!value) return '';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';

    return new Intl.DateTimeFormat('en-AU', {
        dateStyle: 'medium',
    }).format(date);
};

// --- Computed list properties ---
const totalUsers = computed(() => props.data?.total_users ?? 0);
const totalCourses = computed(() => props.data?.total_courses ?? 0);
const totalEnrolments = computed(() => props.data?.total_enrolments ?? 0);
const recentUsers = computed(() => props.data?.recent_users ?? []);
</script>

<template>
    <div>
        <!-- Statistics Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Users -->
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center">
                <div class="p-3 bg-blue-50 dark:bg-blue-950/40 rounded-lg mr-4 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Users</p>
                    <h4 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ totalUsers }}</h4>
                </div>
            </div>

            <!-- Total Courses -->
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center">
                <div class="p-3 bg-green-50 dark:bg-green-950/40 rounded-lg mr-4 text-green-600 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Courses</p>
                    <h4 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ totalCourses }}</h4>
                </div>
            </div>

            <!-- Total Enrolments -->
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center">
                <div class="p-3 bg-purple-50 dark:bg-purple-950/40 rounded-lg mr-4 text-purple-600 dark:text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Enrolments</p>
                    <h4 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ totalEnrolments }}</h4>
                </div>
            </div>
        </div>

        <!-- Recent Users Table Section -->
        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 w-full mt-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Recently Joined Users</h3>
            </div>

            <div class="w-full min-w-full overflow-x-auto">
                <table class="w-full min-w-full text-left table-auto border-collapse">
                    <thead>
                        <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700 text-left">
                            <th class="px-6 py-3">S.N.</th>
                            <th class="px-6 py-3">First Name</th>
                            <th class="px-6 py-3">Last Name</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Joined Date</th>
                            <th class="px-6 py-3 w-full"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <template v-if="recentUsers.length > 0">
                            <tr v-for="(user, index) in recentUsers" :key="user.id" class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.first_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.role }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.status }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ formatDate(user.created_at) }}</td>
                                <td class="px-6 py-4"></td>
                            </tr>
                        </template>

                        <template v-else>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 italic font-medium bg-white dark:bg-gray-900">No users have joined the platform yet.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
