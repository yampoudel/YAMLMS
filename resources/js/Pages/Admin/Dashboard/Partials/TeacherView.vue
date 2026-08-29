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
        }),
    },
});

// --- Computed list properties ---
const totalUsers = computed(() => props.data?.total_users ?? 0);
const totalCourses = computed(() => props.data?.total_courses ?? 0);
const totalEnrolments = computed(() => props.data?.total_enrolments ?? 0);
</script>

<template>
    <div>
        <!-- Statistics Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Users (Enrolled in this Teacher's courses) -->
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

            <!-- Total Courses (Created by this Teacher) -->
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

            <!-- Total Enrolments (Inside this Teacher's courses) -->
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
    </div>
</template>
