<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// --- Props ---
const props = defineProps({
    users: { type: [Object, Array], required: true },
    filters: { type: Object, required: true },
});

// --- Context & State ---
const page = usePage();
const currentUser = computed(() => page.props.auth?.user ?? null);
const isSearchOpen = ref(typeof window !== 'undefined' && new URLSearchParams(window.location.search).has('search'));

// --- Form State & Watchers ---
const form = ref({
    role: props.filters?.role ?? '',
    status: props.filters?.status ?? '',
});

watch(
    () => props.filters,
    (newFilters) => {
        form.value.role = newFilters?.role ?? '';
        form.value.status = newFilters?.status ?? '';
    },
    { deep: true },
);

// --- Actions ---
const handleSearch = () => {
    router.get(
        route('users.index'),
        {
            role: form.value.role || undefined,
            status: form.value.status || undefined,
            search: 1,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('users.destroy', id), {
            preserveState: true,
            replace: true,
        });
    }
};

// Computed list properties
const userList = computed(() => (Array.isArray(props.users) ? props.users : (props.users?.data ?? [])));
const totalUsers = computed(() => props.users?.total ?? userList.value.length ?? 0);
const paginationLinks = computed(() => (Array.isArray(props.users?.links) ? props.users.links : []));
</script>

<template>
    <!-- Main Page Layout Wrapper -->
    <AuthenticatedLayout title="Users">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Page Header / Actions -->
            <div class="flex flex-col gap-4 px-6 py-5 border-b border-gray-200 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Users</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage users, roles, account status and enrol to the course for respective user.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Add User Action -->
                    <Link
                        :href="route('users.create')"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-full shadow-sm hover:bg-indigo-700 transition-all duration-200">
                        + Add User
                    </Link>

                    <!-- Enrolment Navigation Link -->
                    <Link
                        :href="route('enrolments.index')"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-full shadow-sm hover:bg-indigo-700 transition-all duration-200">
                        Enrolment List
                    </Link>

                    <!-- Search Toggle Button -->
                    <button
                        @click="isSearchOpen = !isSearchOpen"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer">
                        <svg xmlns="http://w3.org" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>
                        <span>{{ isSearchOpen ? 'Hide Search' : 'Search' }}</span>
                    </button>
                </div>
            </div>

            <!-- Search Filters -->
            <div v-if="isSearchOpen" class="px-6 py-5 border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/40">
                <form @submit.prevent="handleSearch" class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
                    <!-- User Type Filter -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Type</label>
                        <select
                            id="role"
                            v-model="form.role"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            <option value="">All Types</option>
                            <option value="Admin">Admin</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Learner">Learner</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select
                            id="status"
                            v-model="form.status"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            <option value="">All</option>
                            <option value="Active">Active</option>
                            <option value="Disabled">Disabled</option>
                        </select>
                    </div>

                    <!-- Filter Control Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200 cursor-pointer">
                            Search
                        </button>
                        <Link
                            :href="route('users.index')"
                            class="inline-flex items-center justify-center rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-6 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                            Reset
                        </Link>
                    </div>
                </form>
            </div>

            <!-- Flash Notification -->
            <div class="px-6 pt-5">
                <FlashNotification />
            </div>

            <!-- Total Records Display -->
            <div class="flex items-center justify-between px-6 py-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Total Users:
                    <span class="font-semibold text-gray-900 dark:text-white">{{ totalUsers }}</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="px-6 pb-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full min-w-[1000px] divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Table Header -->
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="w-16 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">S.N.</th>
                                <th class="w-20 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Profile</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">First Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Last Name</th>
                                <th class="w-28 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Role</th>
                                <th class="w-32 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Login</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Email</th>
                                <!-- Minimized status column to w-24 -->
                                <th class="w-24 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Status</th>
                                <!-- Increased action column to w-48 -->
                                <th
                                    v-if="currentUser?.role === 'Admin' || currentUser?.role === 'Teacher'"
                                    class="w-48 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            <template v-if="userList.length">
                                <tr v-for="(user, index) in userList" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ (props.users?.from ?? 1) + index }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <img
                                            :src="user.image_path_url || '/images/default-avatar.png'"
                                            :alt="user.first_name + ' Profile'"
                                            class="w-8 h-8 rounded-full object-cover border dark:border-gray-600" />
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ user.first_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ user.last_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ user.role }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ user.login ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ user.email }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span
                                            :class="
                                                user.status === 'Active'
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                            "
                                            class="px-2.5 py-1 rounded-full text-xs font-medium">
                                            {{ user.status }}
                                        </span>
                                    </td>
                                    <td v-if="currentUser?.role === 'Admin' || currentUser?.role === 'Teacher'" class="px-4 py-3 text-sm text-center">
                                        <div class="flex items-center justify-center gap-3 whitespace-nowrap">
                                            <Link
                                                :href="route('enrolments.create', user.id)"
                                                class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 hover:underline font-semibold">
                                                Course Enrol
                                            </Link>
                                            <Link
                                                :href="route('users.edit', user.id)"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline font-semibold">
                                                Edit
                                            </Link>
                                            <button
                                                @click="deleteUser(user.id)"
                                                type="button"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:underline font-semibold cursor-pointer">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td :colspan="currentUser?.role === 'Admin' || currentUser?.role === 'Teacher' ? 9 : 8" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No users found matching your filters.
                                </td>
                            </tr>
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
