<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">My Learning Journey</h3>

    <!-- Dynamic Payment Success Alert Banner Component -->
    @if(request()->get('payment_success') == '1')
        <div id="payment-success-alert" class="mb-6 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-500 rounded-full text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-extrabold text-sm">Payment Confirmed!</p>
                    <p class="text-xs text-emerald-700 font-medium">Your course transaction cleared successfully. Happy learning!</p>
                </div>
            </div>
            <button onclick="document.getElementById('payment-success-alert').remove()" class="text-emerald-400 hover:text-emerald-600 focus:outline-none p-1 rounded-lg hover:bg-emerald-100/50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if($enrolled_courses->isEmpty())
        <div class="bg-blue-50 border border-blue-200 p-8 mb-8 rounded-xl text-center">
            <p class="text-blue-700 font-medium">You aren't enrolled in any courses yet.</p>
        </div>
        <div class="text-center">
            <a href="{{ route('courses.index') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">Browse Course</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrolled_courses as $course)
                {{-- Get the progress record for this course --}}
                @php
                    $progress = auth()->user()->courseProgress->where('course_id', $course->id)->first();
                @endphp

                <!-- Card Layout Wrapper -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col h-full">
                    <div class="h-32 bg-indigo-600 flex items-center justify-center relative">
                        @if ($course->image_path)
                            <img src="{{ asset('storage/'. $course->image_path) }}" alt="{{ $course->title }}" class="w-full h-full object-cover select-one">
                        @else
                            <span class="text-white text-3xl font-bold">{{ substr($course->title, 0, 1) }}</span>
                        @endif

                        {{-- Show a progress percentage badge if they've started and aren't pending payment --}}
                        @if($progress && $progress->progress_percentage > 0 && $course->pivot->status !== 'Pending_Payment')
                            <div class="absolute bottom-2 right-2 bg-white/90 px-2 py-1 rounded-lg text-[10px] font-bold text-indigo-700">
                                {{ $progress->progress_percentage }}% Done
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h4 class="font-bold text-gray-900 mb-1 px-4 truncate">{{ $course->title }}</h4>
                        <p class="text-xs text-gray-500 mb-4 px-4 line-clamp-2">{{ $course->description }}</p>

                        <!-- Bottom action block grouping -->
                        <div class="mt-auto">
                            {{-- CASE 1: Render the payment forms only if checkout status is pending --}}
                            @if ($course->pivot->status === 'Pending_Payment')
                                <!-- Stripe Payment Container -->
                                <div class="pt-4 border-t border-gray-100 flex flex-col gap-3">
                                    <div class="flex items-center justify-between text-xs text-gray-500 font-medium px-1">
                                        <span>Course Price:</span>
                                        <span class="text-gray-900 font-bold">${{ number_format($course->price, 2) }} AUD</span>
                                    </div>

                                    <!-- Hidden form drawer that opens Stripe Elements overlay -->
                                    <div id="stripe-container-{{ $course->id }}" class="hidden my-2 bg-gray-50 p-4 rounded-xl border border-gray-200 text-left">
                                        <form id="payment-form-{{ $course->id }}">
                                            <div id="payment-element-{{ $course->id }}" class="mb-4"></div>
                                            <button type="submit" id="submit-btn-{{ $course->id }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition shadow-sm">
                                                Confirm Payment
                                            </button>
                                            <div id="error-message-{{ $course->id }}" class="text-red-500 mt-2 text-xs hidden"></div>
                                        </form>
                                    </div>

                                    <!-- Main Sticky Checkout Trigger Button -->
                                    <button onclick="openStripeModal('{{ $course->id }}')"
                                            id="buy-btn-{{ $course->id }}"
                                            class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-sm text-sm focus:outline-none">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://w3.org">
                                            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                                        </svg>
                                        Buy Now with Stripe
                                    </button>
                                </div>

                            {{-- CASE 2: Render progress metrics and links if course is Active or Completed --}}
                            @elseif ($course->pivot->status === 'Active' || $course->pivot->status === 'Completed')
                                {{-- Show progress bar --}}
                                <div class="w-full bg-gray-100 h-1.5 rounded-full mb-4">
                                    <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $progress->progress_percentage ?? 0 }}%"></div>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                    <span class="text-xs px-4 py-2 font-medium text-gray-400">{{ $course->lessons->count() }} Lessons</span>

                                    {{-- Start or Resume navigation --}}
                                    @if(!$progress || $progress->status === 'Not Started')
                                        <a href="{{ route('lessons.start', $course) }}" class="text-sm px-4 py-2 font-bold text-green-600 hover:text-green-700 transition">
                                            Start Course →
                                        </a>
                                    @else
                                        <a href="{{ route('lessons.play', $course) }}" class="text-sm px-4 py-2 font-bold text-indigo-600 hover:text-indigo-800 transition">
                                            {{ $progress->status === 'Completed' ? 'Review' : 'Resume' }} →
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
    <script>
        const StripePaymentGateway = {
            stripeInstance: null,
            isLibraryLoading: false,
            elementsInstance: null,

            // Secure library initialization hook
            init: function() {
                if (window.Stripe) {
                    this.stripeInstance = Stripe("{{ config('services.stripe.key') }}");
                    return;
                }

                if (this.isLibraryLoading) return;
                this.isLibraryLoading = true;

                const script = document.createElement('script');
                script.src = "https://js.stripe.com/dahlia/stripe.js";
                script.type = "text/javascript";
                script.async = true;

                script.onload = () => {
                    this.stripeInstance = window.Stripe("{{ config('services.stripe.key') }}");
                    console.log("⚡ Stripe secure client engine initialized successfully.");
                };

                script.onerror = () => {
                    console.error("❌ Failed to resolve Stripe from official remote CDN networks.");
                };

                document.head.appendChild(script);
            },

            // Intent authorization and layout form mounting method
            openForm: async function(courseId) {
                if (!this.stripeInstance) {
                    this.init();
                    await new Promise(resolve => setTimeout(resolve, 500));
                    if (!this.stripeInstance) {
                        alert("Secure payment gateway is taking a moment to load. Please try clicking again.");
                        return;
                    }
                }

                const buyBtn = document.getElementById(`buy-btn-${courseId}`);
                const container = document.getElementById(`stripe-container-${courseId}`);

                buyBtn.disabled = true;
                buyBtn.innerText = "Authorizing checkout...";

                try {
                    const response = await fetch(`/api/integrations/stripe/intent/${courseId}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    const result = await response.json();

                    if (result.status === 'error') {
                        alert(result.message);
                        buyBtn.disabled = false;
                        buyBtn.innerText = "Buy Now with Stripe";
                        return;
                    }

                    // Map elements instantiation to global gateway tracking instance
                    this.elementsInstance = this.stripeInstance.elements({ clientSecret: result.client_secret });
                    const paymentElement = this.elementsInstance.create("payment");
                    await paymentElement.mount(`#payment-element-${courseId}`);

                    // Expand drawer window visual interface items
                    container.classList.remove('hidden');
                    buyBtn.classList.add('hidden');

                    // Dynamically map form listener intercept parameters
                    const form = document.getElementById(`payment-form-${courseId}`);
                    form.addEventListener('submit', (e) => this.handlePaymentSubmit(e, courseId));

                } catch (error) {
                    console.error("Checkout setup failed:", error);
                    alert("Could not build secure payment interface. Please check your network connection.");
                    buyBtn.disabled = false;
                    buyBtn.innerText = "Buy Now with Stripe";
                }
            },

            // Gateway processing interception method
            handlePaymentSubmit: async function(event, courseId) {
                event.preventDefault(); // Suspend standard post-back reloads

                const submitBtn = document.getElementById(`submit-btn-${courseId}`);
                const errorDiv = document.getElementById(`error-message-${courseId}`);

                submitBtn.disabled = true;
                submitBtn.innerText = "Processing transaction...";
                errorDiv.classList.add('hidden');

                try {
                    // Send transaction specifications straight to Stripe backend arrays
                    const { error } = await this.stripeInstance.confirmPayment({
                        elements: this.elementsInstance,
                        confirmParams: {
                            return_url: `${window.location.origin}/dashboard?payment_success=1`,
                        },
                    });

                    if (error) {
                        errorDiv.innerText = error.message;
                        errorDiv.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.innerText = "Confirm Payment";
                    }

                } catch (error) {
                    console.error("Stripe Client Execution Error:", error);
                    errorDiv.innerText = "An unexpected network event occurred. Please verify your details.";
                    errorDiv.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.innerText = "Confirm Payment";
                }
            }
        };

        // Fire initialization routines immediately upon script discovery
        StripePaymentGateway.init();

        // Structural bridge trigger redirection method mapping links
        function openStripeModal(courseId) {
            StripePaymentGateway.openForm(courseId);
        }

        // Wipe URL param strings cleanly so subsequent refreshes don't show duplicates
        if (window.location.search.includes('payment_success=1')) {
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
        }
    </script>
@endpush
