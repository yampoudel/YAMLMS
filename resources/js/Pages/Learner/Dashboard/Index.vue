<script setup>
import { ref, onMounted, computed, nextTick } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import { loadStripe } from '@stripe/stripe-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashNotification from '@/Components/FlashNotification.vue';

// --- Props ---
const props = defineProps({
    data: { type: [Object, Array], required: true },
    stripe_publishable_key: { type: String, required: false, default: 'pk_test' },
});

// --- Context & State ---
const page = usePage();
const showPaymentSuccess = ref(false);
const activeStripeContainers = ref({});
const stripeInstance = ref(null);
const activeElements = ref({});
const processingPayments = ref({});

// --- Computed list properties ---
const data = computed(() => (Array.isArray(props.data) ? { enrolled_courses: [] } : props.data ? props.data : { enrolled_courses: [] }));
const user = computed(() => page.props.auth?.user ?? null);

// --- Hooks ---
onMounted(async () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('payment_success') === '1') {
        showPaymentSuccess.value = true;
        router.reload({ only: ['data'] });
    }

    try {
        stripeInstance.value = await loadStripe(props.stripe_publishable_key);
    } catch (e) {
        console.error('Stripe failed to initialize:', e);
    }
});

// --- Actions ---
const openStripeModal = async (courseId) => {
    activeStripeContainers.value[courseId] = !activeStripeContainers.value[courseId];

    if (!activeStripeContainers.value[courseId]) return;

    await nextTick();

    if (!stripeInstance.value) {
        alert('Stripe module is still initializing. Please try again.');
        activeStripeContainers.value[courseId] = false;
        return;
    }

    if (activeElements.value[courseId]) return;

    try {
        const response = await axios.post(`/api/integrations/stripe/intent/${courseId}`);

        if (response.data.status === 'error') {
            alert(response.data.message);
            activeStripeContainers.value[courseId] = false;
            return;
        }

        const clientSecret = response.data.client_secret;

        if (!clientSecret) {
            alert('Stripe payload missing token client secret parameters.');
            activeStripeContainers.value[courseId] = false;
            return;
        }

        const elements = stripeInstance.value.elements({ clientSecret });
        const paymentElement = elements.create('payment');

        paymentElement.mount(`#payment-element-${courseId}`);
        activeElements.value[courseId] = { elements, paymentElement };
    } catch (error) {
        console.error(error);
        alert('Failed to establish a validation link session with the gateway infrastructure.');
        activeStripeContainers.value[courseId] = false;
    }
};

const handlePaymentSubmit = async (courseId) => {
    if (processingPayments.value[courseId]) return;
    processingPayments.value[courseId] = true;

    const targetFormInstance = activeElements.value[courseId];

    if (!targetFormInstance || !stripeInstance.value) return;

    const { error } = await stripeInstance.value.confirmPayment({
        elements: targetFormInstance.elements,
        confirmParams: {
            return_url: `${window.location.origin}${window.location.pathname}?payment_success=1`,
        },
    });

    if (error) {
        const errorDiv = document.getElementById(`error-message-${courseId}`);
        if (errorDiv) {
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('hidden');
        }
        processingPayments.value[courseId] = false;
    }
};

const closeSuccessBanner = () => {
    showPaymentSuccess.value = false;
    if (typeof window !== 'undefined') {
        const cleanUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
        window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
    }
};

// --- Helpers ---
const getCourseProgress = (courseId) => {
    if (!user.value?.course_progress) return { progress_percentage: 0, status: 'Not Started' };
    return user.value.course_progress.find((p) => p.course_id === courseId) ?? { progress_percentage: 0, status: 'Not Started' };
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-AU', { style: 'currency', currency: 'AUD' }).format(amount);
};
</script>

<template>
    <AuthenticatedLayout title="My Learning">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">My Learning Journey</h3>

                <!-- Flash Notification Component -->
                <div class="mb-5">
                    <FlashNotification />
                </div>

                <!-- Payment Success Alert Banner -->
                <div
                    v-if="showPaymentSuccess"
                    id="payment-success-alert"
                    class="mb-6 flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl shadow-sm transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-500 rounded-full text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-extrabold text-sm">Payment Confirmed!</p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400 font-medium">Your course transaction cleared successfully. Happy learning!</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="closeSuccessBanner"
                        class="text-emerald-400 hover:text-emerald-600 focus:outline-none p-1 rounded-lg hover:bg-emerald-100/50 dark:hover:bg-emerald-900/30 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- No courses assigned -->
                <template v-if="!data.enrolled_courses || data.enrolled_courses.length === 0">
                    <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 p-8 mb-8 rounded-xl text-center">
                        <p class="text-blue-700 dark:text-blue-300 font-medium">You aren't enrolled in any courses yet.</p>
                    </div>
                    <div class="text-center">
                        <Link :href="route('courses.index')" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                            Browse Course
                        </Link>
                    </div>
                </template>
                <!-- Enrolled courses list -->
                <template v-else>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="course in data.enrolled_courses"
                            :key="course.id"
                            class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition flex flex-col h-full">
                            <!-- Card Background Thumbnail Banner Core -->
                            <div class="h-32 bg-indigo-600 flex items-center justify-center relative">
                                <template v-if="course.image_path">
                                    <img :src="`/storage/${course.image_path}`" :alt="course.title" class="w-full h-full object-cover select-none" />
                                </template>
                                <template v-else>
                                    <span class="text-white text-3xl font-bold uppercase">{{ course.title.substring(0, 1) }}</span>
                                </template>

                                <!-- Show progressive percentage badge conditionally -->
                                <div
                                    v-if="getCourseProgress(course.id).progress_percentage > 0 && course.pivot?.status !== 'Pending_Payment'"
                                    class="absolute bottom-2 right-2 bg-white/90 dark:bg-gray-900/90 px-2 py-1 rounded-lg text-[10px] font-bold text-indigo-700 dark:text-indigo-400">
                                    {{ getCourseProgress(course.id).progress_percentage }}% Done
                                </div>
                            </div>

                            <!-- Course info container -->
                            <div class="p-5 flex-1 flex flex-col">
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1 px-4 truncate">{{ course.title }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 px-4 line-clamp-2">{{ course.description }}</p>

                                <div class="mt-auto">
                                    <!-- Stripe payment options -->
                                    <template v-if="course.pivot?.status === 'Pending_Payment'">
                                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-3">
                                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 font-medium px-1">
                                                <span>Course Price:</span>
                                                <span class="text-gray-900 dark:text-white font-bold">{{ formatCurrency(course.price) }}</span>
                                            </div>

                                            <!-- Stripe input fields -->
                                            <div
                                                v-show="activeStripeContainers[course.id]"
                                                class="my-2 bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 text-left">
                                                <form :id="`payment-form-${course.id}`" @submit.prevent="handlePaymentSubmit(course.id)">
                                                    <div :id="`payment-element-${course.id}`" class="mb-4"></div>
                                                    <button
                                                        type="submit"
                                                        :disabled="processingPayments[course.id]"
                                                        :id="`submit-btn-${course.id}`"
                                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                                        {{ processingPayments[course.id] ? 'Processing...' : 'Confirm Payment' }}
                                                    </button>
                                                    <div :id="`error-message-${course.id}`" class="text-red-500 mt-2 text-xs hidden"></div>
                                                </form>
                                            </div>

                                            <!-- Checkout Trigger Button -->
                                            <button
                                                type="button"
                                                @click="openStripeModal(course.id)"
                                                :id="`buy-btn-${course.id}`"
                                                class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-sm text-sm focus:outline-none">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z" />
                                                </svg>
                                                {{ activeStripeContainers[course.id] ? 'Close Checkout Panel' : 'Buy Now with Stripe' }}
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Course progress layout -->
                                    <template v-else-if="course.pivot?.status === 'Active' || course.pivot?.status === 'Completed'">
                                        <!-- Show progress bar -->
                                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full mb-4">
                                            <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" :style="{ width: `${getCourseProgress(course.id).progress_percentage}%` }"></div>
                                        </div>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-50 dark:border-gray-800">
                                            <span class="text-xs px-4 py-2 font-medium text-gray-400 dark:text-gray-500">{{ course.lessons_count ?? 0 }} Lessons</span>

                                            <!-- Start or Resume navigation -->
                                            <Link
                                                v-if="getCourseProgress(course.id).status === 'Not Started'"
                                                :href="route('lessons.start', course.id)"
                                                class="text-sm px-4 py-2 font-bold text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 transition">
                                                Start Course →
                                            </Link>
                                            <Link
                                                v-else
                                                :href="route('lessons.play', course.id)"
                                                class="text-sm px-4 py-2 font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                                Resume Course →
                                            </Link>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
