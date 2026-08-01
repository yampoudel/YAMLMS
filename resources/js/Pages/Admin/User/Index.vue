<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import FlashNotification from '@/Components/FlashNotification.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Ingest structural properties from your UserController array payload
const props = defineProps({
    users: Object,
    filters: Object,
});

// Map global context states and notifications from the Inertia page instance
const page = usePage();
const currentUser = computed(() => page.props.auth?.user || null);

// Drive filter panel visibilities matching your request search state logic
const isSearchOpen = ref(new URLSearchParams(window.location.search).has('search'));

// Track input form elements reactively
const form = ref({
    role: props.filters?.role || '',
    status: props.filters?.status || ''
});

// Fire search values straight through the SPA routing engine via explicit strings
const handleSearch = () => {
    router.get('/users', {
        ...form.value,
        search: 1
    }, {
        preserveState: true,
        replace: true
    });
};

// Bind explicit row row-deletion actions directly to your URL query pipeline
const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(`/users/${id}`);
    }
};
</script>

<template>
    <!-- Open your master authenticated dashboard shell envelope layout here -->
    <AuthenticatedLayout title="Users">

        <!-- Standalone workspace presentation card container block -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">

            <!-- Core Action Controls Row -->
            <div class="flex items-center justify-between w-full pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
                <div class="flex gap-4">
                    <Link href="/users/create"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-full shadow-sm hover:bg-indigo-700 transition-all duration-200">
                        + Add User
                    </Link>

                    <Link href="/enrolments"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-full shadow-sm hover:bg-indigo-700 transition-all duration-200">
                        Enrolment List
                    </Link>

                    <button @click="isSearchOpen = !isSearchOpen"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer">
                        <svg xmlns="http://w3.org" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>
                        <span class="opacity-90">Search</span>
                    </button>
                </div>
            </div>

            <!-- Expandable Search Filter Drawer Tray Menu -->
            <div v-if="isSearchOpen" class="w-full mb-6">
                <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <form @submit.prevent="handleSearch" class="grid gap-4 lg:grid-cols-[1fr_auto] items-end">
                        <div class="grid gap-4 sm:grid-cols-2 flex-1">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Type</label>
                                <select id="role" v-model="form.role"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-sm dark:text-white">
                                    <option value="">All Types</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Teacher">Teacher</option>
                                    <option value="Learner">Learner</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select id="status" v-model="form.status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-sm dark:text-white">
                                    <option value="">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Disabled">Disabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200 cursor-pointer">
                                Search
                            </button>
                            <Link href="/users"
                                class="inline-flex items-center justify-center rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-6 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                                Reset
                            </Link>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Flash Notification Component -->
            <FlashNotification />

            <div class="w-full text-gray-600 dark:text-gray-400 pl-2 text-sm mb-4">
                Total Users: <span class="font-bold text-gray-900 dark:text-white">{{ users?.total || 0 }}</span>
            </div>

            <!-- Datagrid Frame Table Layout Layout -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">S.N.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Profile Pic</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">First Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Last Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Login</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th v-if="currentUser?.role === 'Admin' || currentUser?.role === 'Teacher'" class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        <tr v-for="(user, index) in users?.data" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ (users?.from || 1) + index }}</td>
                            <td class="px-4 py-3 text-sm">
                                <img :src="user.image_path_url || '/images/default-avatar.png'" :alt="user.first_name" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-600 shadow-xs" />
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ user.first_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ user.last_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ user.role }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ user.login }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span :class="['px-2 py-0.5 rounded-full text-xs font-semibold inline-block', user.status === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400']">
                                    {{ user.status }}
                                </span>
                            </td>
                            <td v-if="currentUser?.role === 'Admin' || currentUser?.role === 'Teacher'" class="px-4 py-3 text-sm text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a :href="`/users/${user.id}/edit`" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">Edit</a>
                                    <button @click="deleteUser(user.id)" class="text-red-600 dark:text-red-400 hover:underline font-semibold cursor-pointer">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!users?.data || users.data.length === 0">
                            <td colspan="9" class="text-center py-10 text-gray-500 text-sm font-medium">No records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Laravel Shared Pagination Properties Component -->
            <Pagination v-if="users?.links" :links="users.links" />
        </div>
    </AuthenticatedLayout>
</template>
