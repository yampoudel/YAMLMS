<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TrainingPortal | Professional Learning Management</title>
        <link rel="preconnect" href="https://bunny.net">
        <link href="https://bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-white text-slate-900">
        <div class="relative min-h-screen">
            
            <!-- NAVIGATION -->
            <nav class="flex items-center justify-between px-10 py-5 border-b border-slate-200 bg-white sticky top-0 z-50 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded flex items-center justify-center text-white font-black text-xl">Y</div>
                    <span class="font-black text-2xl tracking-tighter text-slate-900 uppercase">Yam<span class="text-indigo-600">LMS</span></span>
                </div>

                <div class="hidden md:flex items-center gap-10 text-sm font-black uppercase tracking-widest text-slate-600">
                    <a href="#services" class="hover:text-indigo-600 transition">Services</a>
                    <a href="#about" class="hover:text-indigo-600 transition">About Us</a>
                    <a href="#courses" class="hover:text-indigo-600 transition">Buy Courses</a>
                    <a href="#contact" class="hover:text-indigo-600 transition">Contact</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-indigo-600 text-white rounded font-bold text-sm hover:bg-indigo-700 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 border-2 border-slate-200 text-slate-900 rounded font-bold text-sm hover:bg-slate-50 transition">Log In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-indigo-600 text-white rounded font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">Sign Up</a>
                        @endif
                    @endauth
                </div>
            </nav>

            <!-- HERO SECTION -->
            <header class="bg-slate-50 py-24 border-b border-slate-200">
                <div class="max-w-7xl mx-auto px-10 text-center">
                    <h1 class="text-6xl font-black text-slate-900 mb-6 leading-tight tracking-tighter">
                        Enterprise-Grade Training <br> <span class="text-indigo-600">For Modern Organisations.</span>
                    </h1>
                    <p class="text-xl text-slate-500 max-w-3xl mx-auto font-medium leading-relaxed mb-10">
                        Whether you are a College, Aged Care provider, NDIS provider or a growing business, our portal delivers secure, compliant, and tracked learning experiences.
                    </p>
                    <a href="#courses" class="inline-block px-12 py-4 bg-indigo-600 text-white rounded-full font-black text-lg shadow-xl hover:scale-105 transition-all">Explore Available Courses</a>
                </div>
            </header>

            <!-- SERVICES SECTION -->
            <section id="services" class="py-24 max-w-7xl mx-auto px-10">
                <h2 class="text-xs font-black text-indigo-600 uppercase tracking-[0.3em] mb-4 text-center">Our Services</h2>
                <h3 class="text-4xl font-black text-slate-900 text-center mb-16 italic">Built for Scalable Learning.</h3>
                
                <div class="grid md:grid-cols-3 gap-12">
                    <div class="space-y-4 p-8 border border-slate-100 rounded-2xl hover:shadow-xl transition">
                        <div class="w-12 h-12 bg-indigo-600 rounded flex items-center justify-center text-white font-bold text-xl">01</div>
                        <h4 class="text-xl font-black">Staff Onboarding</h4>
                        <p class="text-slate-500 leading-relaxed">Automate your induction process. Ensure every new hire receives consistent, trackable training from day one.</p>
                    </div>
                    <div class="space-y-4 p-8 border border-slate-100 rounded-2xl hover:shadow-xl transition">
                        <div class="w-12 h-12 bg-indigo-600 rounded flex items-center justify-center text-white font-bold text-xl">02</div>
                        <h4 class="text-xl font-black">Compliance Tracking</h4>
                        <p class="text-slate-500 leading-relaxed">Mandatory safety and regulatory training with automated reporting for audits and certification.</p>
                    </div>
                    <div class="space-y-4 p-8 border border-slate-100 rounded-2xl hover:shadow-xl transition">
                        <div class="w-12 h-12 bg-indigo-600 rounded flex items-center justify-center text-white font-bold text-xl">03</div>
                        <h4 class="text-xl font-black">Curriculum Hosting</h4>
                        <p class="text-slate-500 leading-relaxed">Colleges and schools can host entire digital semesters with secure access for enrolled students.</p>
                    </div>
                </div>
            </section>

            <!-- ABOUT SECTION -->
            <section id="about" class="py-24 bg-slate-900 text-white">
                <div class="max-w-7xl mx-auto px-10 grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-xs font-black text-indigo-400 uppercase tracking-[0.3em] mb-6">About Us</h2>
                        <h3 class="text-4xl font-black mb-8 leading-tight">Empowering growth through <br> technology-driven education.</h3>
                        <p class="text-slate-400 text-lg leading-relaxed mb-6">
                            TrainingPortal was founded to bridge the gap between traditional learning and the digital workforce. We provide a robust, cloud-based infrastructure that handles the complexity of learning management, so you can focus on the content.
                        </p>
                    </div>
                    <div class="bg-white/5 p-10 rounded-2xl border border-white/10 italic text-2xl font-light text-slate-300 leading-relaxed">
                        "Our mission is to provide the most reliable training infrastructure for organisations that value their staff's development."
                    </div>
                </div>
            </section>

            <!-- BUY COURSES SECTION -->
            <section id="courses" class="py-24 max-w-7xl mx-auto px-10">
                <div class="flex items-end justify-between mb-12 border-b border-slate-100 pb-8">
                    <div>
                        <h2 class="text-3xl font-black">Course Marketplace</h2>
                        <p class="text-slate-500 font-medium">Select a module to begin your professional journey.</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    {{-- THIS IS THE FRONTEND PURCHASE UI --}}
                    @foreach($courses ?? [] as $course)
                        <div class="bg-white border-2 border-slate-100 rounded-2xl p-6 hover:border-indigo-600 transition group">
                            <div class="h-40 bg-slate-100 rounded-xl mb-6 flex items-center justify-center font-black text-slate-300 text-4xl uppercase">
                                {{ substr($course->title, 0, 1) }}
                            </div>
                            <h4 class="text-xl font-black mb-2">{{ $course->title }}</h4>
                            <p class="text-slate-500 text-sm mb-6 line-clamp-2">{{ $course->description }}</p>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-2xl font-black text-indigo-600">$49.00</span>
                                <a href="{{ route('login') }}" class="px-6 py-2 bg-slate-900 text-white rounded font-bold text-xs hover:bg-indigo-600 transition">Buy Now</a>
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- FALLBACK IF NO COURSES IN DB --}}
                    @if(empty($courses))
                        <p class="col-span-3 text-center text-slate-400 py-10 border-2 border-dashed border-slate-100 rounded-2xl">
                            Courses are currently being updated. Please check back shortly.
                        </p>
                    @endif
                </div>
            </section>

            <!-- CONTACT SECTION -->
            <section id="contact" class="py-24 bg-slate-50 border-t border-slate-200 text-center">
                <div class="max-w-2xl mx-auto px-10">
                    <h2 class="text-3xl font-black mb-6">Need a custom solution?</h2>
                    <p class="text-slate-500 mb-10">Contact our enterprise team for bespoke training setups for your organisation.</p>
                    <a href="mailto:support@trainingportal.com" class="text-2xl font-black text-indigo-600 underline">support@trainingportal.com</a>
                </div>
            </section>

            <footer class="py-12 text-center text-slate-400 text-[15px] font-black uppercase tracking-widest border-t border-slate-100">
                &copy; {{ date('Y') }} YamLMS | Engineered by Yam Technologies.
            </footer>
        </div>
    </body>
</html>
