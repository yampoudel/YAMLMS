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

// --- Computed Properties ---
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
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-AU', { style: 'currency', currency: 'AUD' }).format(amount);
};

const getStatusText = (percentage) => {
    return percentage === 100 ? 'Completed' : percentage ? 'In Progress' : 'Not Started';
};

const getAccessBadge = (status) => {
    return status === 'Active' ? { text: 'Access Granted', classes: 'bg-emerald-50 text-emerald-700' } : { text: 'Pending Payment', classes: 'bg-amber-50 text-amber-700' };
};

const getProgressConfig = (progress) => {
    const isCompleted = progress?.completion_status === 'Completed';
    const percent = progress?.progress_percentage ?? 0;

    return {
        text: getStatusText(percent),
        percent: percent,
        width: isCompleted ? 100 : percent,
        textClass: isCompleted ? 'text-emerald-600 font-bold' : 'text-indigo-600',
        barClass: isCompleted ? 'bg-emerald-500' : 'bg-indigo-600',
    };
};
</script>

<template>
    <AuthenticatedLayout title="My Learning">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 light text-slate-900">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">My Learning Journey</h3>

                <div class="mb-5">
                    <FlashNotification />
                </div>

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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <template v-if="!data.enrolled_courses || data.enrolled_courses.length === 0">
                    <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 p-8 mb-8 rounded-xl text-center">
                        <p class="text-blue-700 dark:text-blue-300 font-medium">You aren't enrolled in any courses yet.</p>
                    </div>
                    <div class="text-center">
                        <Link :href="route('courses.index')" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                            Browse Courses
                        </Link>
                    </div>
                </template>

                <template v-else>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
                        <div
                            v-for="course in data.enrolled_courses"
                            :key="course.id"
                            class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition flex flex-col h-fit group">
                            <div class="w-full h-44 bg-slate-100 dark:bg-gray-800 relative overflow-hidden border-b border-gray-100 dark:border-gray-800">
                                <img
                                    v-if="course.course_image_url"
                                    :src="course.course_image_url"
                                    :alt="course.title"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full" :class="getAccessBadge(course.progress?.status).classes">
                                            {{ getAccessBadge(course.progress?.status).text }}
                                        </span>
                                        <span class="text-xs font-medium text-gray-400 italic">{{ course.lessons_count }} Lessons</span>
                                    </div>

                                    <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight mb-1 truncate" :title="course.title">
                                        {{ course.title }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mb-4">
                                        By
                                        <span class="font-bold text-gray-600 dark:text-gray-300">{{ course.creator?.name || 'Instructor' }}</span>
                                    </p>
                                </div>

                                <div v-if="course.progress?.status === 'Active'" class="space-y-2 mt-4">
                                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        <span>
                                            Status:
                                            <strong class="text-indigo-600 dark:text-indigo-400 font-black uppercase ml-0.5">{{ getProgressConfig(course.progress).text }}</strong>
                                        </span>
                                        <span :class="getProgressConfig(course.progress).textClass">{{ getProgressConfig(course.progress).percent }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden border border-gray-50 dark:border-gray-950">
                                        <div
                                            class="h-full rounded-full transition-all duration-500 ease-out"
                                            :class="getProgressConfig(course.progress).barClass"
                                            :style="{ width: getProgressConfig(course.progress).width + '%' }"></div>
                                    </div>
                                </div>

                                <div v-else class="mt-4 pt-4 border-t border-gray-50 dark:border-gray-800 flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Course Price:</span>
                                    <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">
                                        {{ formatCurrency(course.price ?? 0) }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-4">
                                <div v-if="course.progress?.status !== 'Active'" class="w-full">
                                    <button
                                        @click="openStripeModal(course.id)"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest py-3 px-4 rounded-xl text-center shadow-md transition-all">
                                        {{ activeStripeContainers[course.id] ? 'Cancel Checkout' : 'Pay Now & Start' }}
                                    </button>

                                    <div
                                        v-show="activeStripeContainers[course.id]"
                                        class="mt-4 p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-inner transition-all duration-300">
                                        <div :id="`payment-element-${course.id}`" class="mb-4"></div>
                                        <div :id="`error-message-${course.id}`" class="hidden mb-3 p-3 bg-rose-50 text-rose-700 text-xs font-bold rounded-lg border border-rose-200"></div>
                                        <button
                                            @click="handlePaymentSubmit(course.id)"
                                            type="button"
                                            :disabled="processingPayments[course.id]"
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest py-3 rounded-xl transition shadow-md disabled:opacity-50">
                                            {{ processingPayments[course.id] ? 'Processing Order...' : 'Confirm Secure Payment' }}
                                        </button>
                                    </div>
                                </div>

                                <div v-else class="w-full flex flex-col gap-2">
                                    <template v-if="course.progress?.completion_status === 'Completed'">
                                        <div class="grid grid-cols-2 gap-3 w-full items-center">
                                            <Link
                                                :href="route('lessons.play', { course: course.id, lesson: course.progress?.first_lesson_id })"
                                                class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-black text-[10px] uppercase tracking-wider py-3.5 px-3 rounded-xl text-center shadow-md transition-all active:scale-95">
                                                Review Course
                                            </Link>
                                            <Link
                                                :href="route('certificates.view', { course: course.id, user_id: user?.id })"
                                                target="_blank"
                                                class="inline-flex items-center justify-center gap-1.5 bg-white dark:bg-gray-800 border-2 border-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 font-black text-[10px] uppercase tracking-wider py-3 px-3 rounded-xl text-center shadow-sm hover:shadow transition-all active:scale-95">
                                                <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Certificate
                                            </Link>
                                        </div>
                                    </template>

                                    <Link
                                        v-else-if="course.progress?.completion_status === 'In_Progress'"
                                        :href="route('lessons.play', { course: course.id, lesson: course.progress?.first_lesson_id })"
                                        class="w-full block bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest py-3 px-4 rounded-xl text-center shadow-md transition-all active:scale-95">
                                        Resume Course
                                    </Link>

                                    <Link
                                        v-else
                                        :href="route('lessons.play', { course: course.id, lesson: course.progress?.first_lesson_id })"
                                        class="w-full block bg-slate-900 hover:bg-slate-800 text-white font-black text-xs uppercase tracking-widest py-3 px-4 rounded-xl text-center shadow-md transition-all active:scale-95">
                                        Start Course
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
