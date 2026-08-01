<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

// 1. Fetch user data details globally from the shared Inertia session state
const page = usePage();
const currentUser = computed(() => page.props.auth?.user || { role: '' });

// 2. Build a path validation helper to replace Blade's request()->routeIs() utility
const isCurrentRoute = (pathPattern) => {
    return window.location.pathname.startsWith(pathPattern);
};
</script>

<template>
    <!-- Master Outer Layout Shell - Matches your exact Blade class matrix -->
    <aside class="w-80 flex-shrink-0 min-h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col z-20">

        <!-- Top Section (Logo & Navigation Content) -->
        <div class="flex-1">

            <!-- APPLICATION LOGO AREA (Legacy page: use standard anchor tag) -->
            <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-700">
                <a href="/dashboard" class="flex items-center">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="ml-3 text-lg font-bold text-gray-800 dark:text-white tracking-tight">
                        LMS {{ currentUser.role }}
                    </span>
                </a>
            </div>

            <!-- Management Links — Visible to Admins and Teachers -->
            <nav v-if="currentUser.role === 'Admin' || currentUser.role === 'Teacher'" class="py-4 px-3 space-y-2">

                <!-- User Management (MODERN INERTIA VUE MODULE: Keep as standard Link component) -->
                <Link href="/users"
                    :class="[isCurrentRoute('/users') ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900']"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group">
                    <svg :class="[isCurrentRoute('/users') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500']"
                        class="w-5 h-5 mr-3" xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    USER MANAGEMENT
                </Link>

                <!-- Course Management (LEGACY BLADE VIEW: Swapped out for plain HTML anchor tag) -->
                <a href="/courses"
                    :class="[isCurrentRoute('/courses') ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900']"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group">
                    <svg :class="[isCurrentRoute('/courses') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500']"
                        class="w-5 h-5 mr-3" xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                    COURSE MANAGEMENT
                </a>

                <!-- Enrolments (LEGACY BLADE VIEW: Swapped out for plain HTML anchor tag) -->
                <a href="/enrolments"
                    :class="[isCurrentRoute('/enrolments') ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900']"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group">
                    <svg :class="[isCurrentRoute('/enrolments') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500']"
                        class="w-5 h-5 mr-3" xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    ENROLMENTS
                </a>
                <!-- Lessons Management (LEGACY BLADE VIEW: Swapped out for plain HTML anchor tag) -->
                <a href="/lessons"
                    :class="[isCurrentRoute('/lessons') ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900']"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group">
                    <svg :class="[isCurrentRoute('/lessons') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500']"
                        class="w-5 h-5 mr-3" xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    LESSON MANAGEMENT
                </a>

                <!-- Training Records (LEGACY BLADE VIEW: Swapped out for plain HTML anchor tag) -->
                <a href="/trainingrecords"
                    :class="[isCurrentRoute('/trainingrecords') ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900']"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group">
                    <svg :class="[isCurrentRoute('/trainingrecords') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500']"
                        class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    TRAINING RECORDS
                </a>
            </nav>

            <!-- Learning Link Menu Profile — Visible to Students/Learners -->
            <nav v-if="currentUser.role === 'Learner'" class="py-4 px-3 space-y-2">

                <!-- My Learning (LEGACY BLADE VIEW: Swapped out for plain HTML anchor tag) -->
                <a href="/dashboard"
                    :class="[window.location.pathname === '/dashboard' ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900']"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group">
                    <svg :class="[window.location.pathname === '/dashboard' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500']"
                        class="w-5 h-5 mr-3" xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 15l7.74-4.853a1 1 0 000-1.707L12 3.586 4.26 8.44a1 1 0 000 1.707z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v6M12 21a2.121 2.121 0 01-3-3m3 3a2.121 2.121 0 003-3m-6 0h6" />
                    </svg>
                    MY LEARNING
                </a>

                <!-- Training Records Student (LEGACY BLADE VIEW: Swapped out for plain HTML anchor tag) -->
                <a href="/trainingrecords"
                    :class="[isCurrentRoute('/trainingrecords') ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900']"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group">
                    <svg :class="[isCurrentRoute('/trainingrecords') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500']"
                        class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    TRAINING RECORDS
                </a>
            </nav>
        </div>
    </aside>
</template>

