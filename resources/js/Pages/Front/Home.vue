<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

// --- Props ---
const props = defineProps({
    auth: { type: Object, required: false, default: () => ({ user: null }) },
    courses: { type: [Object, Array], required: false, default: () => [] },
});

// --- Formatters & Helpers ---
const getFallbackChar = (title) => {
    return title ? title.charAt(0).toUpperCase() : 'C';
};

// Computed list properties
const courseList = computed(() => (Array.isArray(props.courses) ? props.courses : (props.courses?.data ?? [])));

// Get the current year once when the component builds to prevent SSR hydration mismatches
const currentYear = new Date().getFullYear();
</script>

<template>
    <!-- Dynamic Page Meta Title -->
    <Head title="Welcome | Enterprise Learning Management" />

    <div class="min-h-screen overflow-x-hidden bg-slate-50 font-sans text-slate-900 antialiased selection:bg-indigo-600 selection:text-white">
        <!-- Background Layer Graphics -->
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -left-32 -top-32 h-[500px] w-[500px] rounded-full bg-indigo-100/50 blur-[120px]"></div>
            <div class="absolute right-0 top-40 h-[400px] w-[400px] rounded-full bg-violet-100/40 blur-[120px]"></div>
        </div>

        <!-- Responsive Header Element-->
        <nav class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex h-[72px] items-center justify-between">
                    <!-- Platform Brand Logo Core (Replaced with Link) -->
                    <Link href="/" class="group flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-lg font-black text-white shadow-lg shadow-indigo-200 transition duration-300 group-hover:scale-105">
                            Y
                        </div>
                        <div class="leading-none">
                            <div class="text-lg font-black tracking-tight text-slate-900">
                                Yam
                                <span class="text-indigo-600">LMS</span>
                            </div>
                            <div class="mt-1 text-[9px] font-bold uppercase tracking-[0.18em] text-slate-400">Learning Platform</div>
                        </div>
                    </Link>

                    <!-- Desktop menu navigation links -->
                    <div class="hidden items-center gap-8 md:flex">
                        <a href="#services" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">Services</a>
                        <a href="#about" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">About Us</a>
                        <a href="#courses" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">Marketplace</a>
                        <a href="#contact" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">Contact</a>
                    </div>

                    <!-- Desktop login and signup buttons -->
                    <div class="hidden items-center gap-3 md:flex">
                        <template v-if="auth?.user">
                            <Link
                                :href="route('dashboard')"
                                class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-indigo-100 transition duration-200 hover:opacity-95 hover:shadow-lg hover:shadow-indigo-200 active:scale-[0.98]">
                                Dashboard
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:text-slate-900 active:scale-[0.98]">
                                Log In
                            </Link>

                            <Link
                                :href="route('register')"
                                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-bold text-white shadow-sm ring-1 ring-slate-900 transition duration-200 hover:bg-indigo-600 hover:ring-indigo-600 hover:shadow-md hover:shadow-indigo-100 active:scale-[0.98]">
                                Sign Up
                            </Link>
                        </template>
                    </div>

                    <div class="flex items-center gap-2 md:hidden">
                        <template v-if="auth?.user">
                            <Link :href="route('dashboard')" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm active:scale-95">Dashboard</Link>
                        </template>

                        <template v-else>
                            <Link :href="route('login')" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                Log In
                            </Link>

                            <Link :href="route('register')" class="rounded-lg bg-slate-950 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-600">Sign Up</Link>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Mobile horizontal scrolling menu bar -->
            <div
                class="flex items-center gap-6 overflow-x-auto whitespace-nowrap border-t border-slate-100 bg-slate-50/50 px-6 py-2.5 md:hidden [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                <a href="#services" class="text-xs font-bold tracking-wide text-slate-500 active:text-indigo-600">Services</a>
                <a href="#about" class="text-xs font-bold tracking-wide text-slate-500 active:text-indigo-600">About Us</a>
                <a href="#courses" class="text-xs font-bold tracking-wide text-slate-500 active:text-indigo-600">Marketplace</a>
                <a href="#contact" class="text-xs font-bold tracking-wide text-slate-500 active:text-indigo-600">Contact</a>
                <span class="inline-block w-4 pointer-events-none"></span>
            </div>
        </nav>

        <!-- Main Layout Header View -->
        <header id="top" class="relative overflow-hidden bg-white">
            <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8 lg:py-28">
                <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
                    <!-- Left Copy Column Context Definition -->
                    <div class="lg:col-span-7">
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-4 py-2 text-xs font-bold text-indigo-700">
                            <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                            Next-Gen Learning Architecture
                        </div>

                        <h1 class="max-w-4xl text-4xl font-black leading-[1.1] tracking-tight text-slate-950 sm:text-6xl lg:text-7xl">
                            Enterprise-Grade Training
                            <span class="block bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent">For Modern Teams.</span>
                        </h1>

                        <p class="mt-6 max-w-2xl text-base font-medium leading-relaxed text-slate-600 sm:text-lg">
                            Whether you are an educational institution, Aged Care provider, or NDIS operator, our cloud framework guarantees secure, highly scalable, and audit-compliant workforce
                            upskilling.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a
                                href="#courses"
                                class="inline-flex w-full sm:w-auto items-center justify-center rounded-2xl bg-slate-950 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-slate-300/40 transition duration-300 hover:-translate-y-1 hover:bg-indigo-600">
                                Explore Open Modules
                                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right Copy Column Visual Analytics Component Dashboard Widget -->
                    <div class="lg:col-span-5 relative w-full">
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-3xl rotate-2 scale-95 opacity-5 blur-sm"></div>
                        <div class="relative overflow-hidden rounded-3xl border border-slate-200/60 bg-white shadow-lg shadow-slate-200/40">
                            <!-- Visible to logged-in users / authenticated learners -->
                            <template v-if="auth?.user">
                                <div class="bg-slate-50/50 p-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Learning Dashboard</p>
                                            <h3 class="mt-1 text-lg font-black text-slate-900">Welcome, {{ auth.user.name || auth.user.first_name || 'Learner' }} 👋</h3>
                                        </div>
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-700 font-bold text-white shadow-sm text-sm">
                                            {{ getFallbackChar(auth.user.name || auth.user.first_name) }}
                                        </div>
                                    </div>

                                    <div class="mt-6 grid grid-cols-3 gap-2.5">
                                        <div class="rounded-xl bg-white p-3 border border-slate-100 shadow-sm text-center">
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Enrolments</p>
                                            <p class="mt-1 text-lg font-black text-slate-900">{{ auth.user.enrolments_count ?? totalCourses }}</p>
                                        </div>
                                        <div class="rounded-xl bg-white p-3 border border-slate-100 shadow-sm text-center">
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Progress</p>
                                            <p class="mt-1 text-lg font-black text-indigo-600">{{ auth.user.global_progress ?? '0' }}%</p>
                                        </div>
                                        <div class="rounded-xl bg-white p-3 border border-slate-100 shadow-sm text-center">
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Certs</p>
                                            <p class="mt-1 text-lg font-black text-slate-900">{{ auth.user.certificates_count ?? '0' }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-5 rounded-xl border border-slate-100 bg-white p-4 shadow-sm text-center space-y-2.5">
                                        <p class="text-xs font-semibold text-slate-500">Your secure learning workspace node is active.</p>
                                        <Link
                                            :href="route('dashboard')"
                                            class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 transition">
                                            Enter Workspace Terminal
                                        </Link>
                                    </div>
                                </div>
                            </template>

                            <!-- Visible to public guests / logged-out users -->
                            <template v-else>
                                <div class="p-6 space-y-5 bg-gradient-to-b from-white to-slate-50">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Repository Telemetry</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">v12.0 Active</span>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-indigo-50 text-indigo-600 font-bold text-xs">✓</div>
                                            <p class="text-xs font-medium text-slate-600">Stripe Payment Intents Server Configurations</p>
                                        </div>
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-indigo-50 text-indigo-600 font-bold text-xs">✓</div>
                                            <p class="text-xs font-medium text-slate-600">Automated DomPDF Compliance Certifications</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 pt-1">
                                        <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-sm">
                                            <div class="text-xl font-black text-slate-800">{{ totalCourses }}</div>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Catalog Modules</div>
                                        </div>
                                        <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-sm">
                                            <div class="text-xl font-black text-slate-800">99.9%</div>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">System Uptime</div>
                                        </div>
                                    </div>

                                    <div class="pt-1">
                                        <Link
                                            :href="route('register')"
                                            class="flex w-full items-center justify-center rounded-xl bg-slate-950 py-3 text-xs font-bold text-white hover:bg-indigo-600 transition shadow-md">
                                            Initialize New Training Profile
                                        </Link>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Service Section -->
        <section id="services" class="py-20 mx-auto max-w-7xl px-6 lg:px-8 scroll-mt-16">
            <div class="mx-auto max-w-2xl text-center space-y-2">
                <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">Structural Offerings</h2>
                <p class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Engineered for Scalable Deployment</p>
            </div>

            <div class="mx-auto mt-12 max-w-2xl lg:max-w-none">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Service card index row loop 1 -->
                    <div class="relative flex flex-col items-start p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:shadow-md transition group">
                        <div
                            class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-sm font-bold text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            01
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5">Staff Onboarding Loops</h3>
                        <p class="text-xs sm:text-sm leading-relaxed text-slate-500">
                            Completely automate corporate entry pipelines. Audit and guarantee every user processes standardized, secure instructional data from day one.
                        </p>
                    </div>
                    <!-- Service card index row loop 2 -->
                    <div class="relative flex flex-col items-start p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:shadow-md transition group">
                        <div
                            class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-sm font-bold text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            02
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5">Compliance Tracking</h3>
                        <p class="text-xs sm:text-sm leading-relaxed text-slate-500">
                            Track high-stakes safety and regulatory records. Access precise automated verification reports optimized for immediate compliance submission.
                        </p>
                    </div>
                    <!-- Service card index row loop 3 -->
                    <div class="relative flex flex-col items-start p-6 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:shadow-md transition group"></div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-slate-950 text-white relative overflow-hidden scroll-mt-16">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-900/30 via-transparent to-transparent pointer-events-none"></div>
            <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-24 items-center">
                    <div class="space-y-4">
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-400">Our Strategic Architecture</div>
                        <h3 class="text-2xl font-extrabold tracking-tight sm:text-4xl leading-tight">Bridging Institutional Operations with Cloud Execution</h3>
                        <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                            Yam LMS architecture handles underlying relational database complexities, cryptographic progress mapping, and high-volume asset distribution natively. Your focus remains
                            entirely allocated to structural curriculum synthesis.
                        </p>
                    </div>
                    <div class="border-l-4 border-indigo-500 pl-6 py-2">
                        <blockquote class="text-lg sm:text-xl font-medium text-slate-200 italic leading-relaxed">
                            "We provide operational performance certainty for high-stakes networks that view ongoing skill mapping not as an operational checking block, but as a strategic
                            infrastructure standard."
                        </blockquote>
                    </div>
                </div>
            </div>
        </section>

        <!-- Course/Marketplace Section  -->
        <section id="courses" class="py-20 mx-auto max-w-7xl px-6 lg:px-8 scroll-mt-16">
            <div class="border-b border-slate-200 pb-5 mb-10">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Course Marketplace</h2>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Select a core syllabus module to provision directly into your training profile node.</p>
            </div>

            <!-- Course grid display layout -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Zero State Condition Handle -->
                <div v-if="!courseList || courseList.length === 0" class="col-span-full border-2 border-dashed border-slate-200 rounded-2xl p-12 text-center text-slate-400 text-sm font-semibold">
                    No active modules provisioned within current node database context.
                </div>

                <!-- Active Course Loop Asset Node -->
                <div
                    v-else
                    v-for="course in courseList"
                    :key="course.id"
                    class="flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm hover:shadow-xl hover:border-slate-300 transition duration-300 group">
                    <div>
                        <!-- Thumbnail Visual Banner Component -->
                        <div class="relative h-44 w-full overflow-hidden bg-slate-50">
                            <!-- Floating Price Badge -->
                            <div class="absolute right-3 top-3 z-10 rounded-lg bg-slate-950/80 px-2.5 py-1 text-xs font-black text-white backdrop-blur-md">
                                {{ course.price && course.price > 0 ? `$${course.price}` : 'FREE' }}
                            </div>

                            <img
                                v-if="course.image_path"
                                :src="`/storage/${course.image_path}`"
                                :alt="course.title"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-102" />
                            <!-- Clean Fallback Avatar Design Layout -->
                            <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50/50 to-violet-50 border-b border-slate-100">
                                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-indigo-500/10 text-xl font-black text-indigo-600">
                                    {{ getFallbackChar(course.title) }}
                                </div>
                            </div>
                        </div>

                        <!-- Card Core Structural Info -->
                        <div class="p-5 space-y-2">
                            <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                {{ course.title || 'Incomplete Node Asset' }}
                            </h3>
                            <p class="text-xs sm:text-sm leading-relaxed text-slate-500 line-clamp-3">
                                {{ course.description || 'No structured description payload supplied for this active element reference.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Enrollment action buttons -->
                    <div class="p-5 pt-0 space-y-3">
                        <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs font-bold text-slate-400">
                            <span>Price:</span>
                            <span :class="course.price && course.price > 0 ? 'text-slate-900' : 'text-emerald-600'">
                                {{ course.price && course.price > 0 ? `$${course.price} AUD` : 'Lifetime Free' }}
                            </span>
                        </div>

                        <!-- For logged-in users -->
                        ) -->
                        <template v-if="auth?.user">
                            <Link
                                :href="route('dashboard')"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-50 py-3 text-xs font-bold text-indigo-700 hover:bg-indigo-600 hover:text-white transition-all duration-200 active:scale-[0.98]">
                                Go to My Portal
                            </Link>
                        </template>

                        <!-- For public guest visitors -->
                        <template v-else>
                            <Link
                                :href="route('register')"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-slate-950 py-3 text-xs font-bold text-white shadow-sm ring-1 ring-slate-900 transition-all duration-200 hover:bg-indigo-600 hover:ring-indigo-600 hover:shadow-md hover:shadow-indigo-100 active:scale-[0.98]">
                                Enroll Now
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Support Section -->
        <section id="contact" class="py-16 bg-gradient-to-b from-white to-slate-50/50 border-t border-slate-200/80 scroll-mt-16">
            <div class="mx-auto max-w-4xl px-6 text-center space-y-5 lg:px-8">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Initialize Corporate Integration Nodes</h2>
                <p class="mx-auto max-w-xl text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                    Interface directly with our technical support cluster to deploy custom enterprise partitions or arrange multi-tenant structures.
                </p>
                <div class="pt-2">
                    <a
                        href="mailto:support@yam-lms.com"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-indigo-600 hover:-translate-y-0.5 transition duration-200">
                        Contact Infrastructure Support
                    </a>
                </div>
            </div>
        </section>

        <!-- Site footer section -->
        <footer class="border-t border-slate-200/60 bg-white py-6 text-center text-[11px] font-bold tracking-wide text-slate-400 uppercase">
            &copy; {{ currentYear }} Yam LMS. Managed Node. All privileges assigned.
        </footer>
    </div>
</template>
