@push('scripts')
   @vite(['resources/js/user/user-validation.js'])
@endpush

<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <!-- SECTION 1: Extreme Left (The Button) -->
            <div class="flex-1 flex justify-start">
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                    <!-- SVG Icon makes the "Back" action clear -->
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ $page_info['back_button'] }}
                </a>
            </div>

            <!-- SECTION 2: Center (The Title) -->
            <div class="flex-1 text-center">
                <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                    {{ $page_info['title'] }}
                </h1>
            </div>

            <!-- SECTION 3: Right Spacer (Keeps the title perfectly in the middle) -->
            <div class="flex-1"></div>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form id="userForm" name="userForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="role"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="Admin"{{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Learner"{{ old('role') == 'Learner' ? 'selected' : '' }}>Learner
                                </option>
                                <option value="Teacher"{{ old('role') == 'Teacher' ? 'selected' : '' }}>Teacher
                                </option>
                            </select>
                            <span class="js-role-error text-red-700 text-sm block mt-1">
                                @error('role')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Login -->
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Login</label>
                            <input type="text" name="login" id="login" value="{{ old('login') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-login-error text-red-700 text-sm block mt-1">
                                @error('login')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First
                                Name</label>
                            <input type="text" name="first_name" id="first_name" value ="{{ old('first_name') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-firstname-error text-red-700 text-sm block mt-1">
                                @error('first_name')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last
                                Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-lastname-error text-red-700 text-sm block mt-1">
                                @error('last_name')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="text" name="email" id="email" value="{{ old('email') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-email-error text-red-700 text-sm block mt-1">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="text" name="password" id="password" value="{{ old('password') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-password-error text-red-700 text-sm block mt-1">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="Active"{{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Disabled"{{ old('status') == 'Disabled' ? 'selected' : '' }}>Disabled
                                </option>
                            </select>
                            <span class="js-status-error text-red-700 text-sm block mt-1">
                                @error('status')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Birthdate -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Date of
                                Birth</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-birthdate-error text-red-700 text-sm block mt-1">
                                @error('birth_date')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-phone-error text-red-700 text-sm block mt-1">
                                @error('phone')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-mobile-error text-red-700 text-sm block mt-1">
                                @error('mobile')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-country-error text-red-700 text-sm block mt-1">
                                @error('country')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-city-error text-red-700 text-sm block mt-1">
                                @error('city')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Postcode -->
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-gray-700 mb-1">Post
                                Code</label>
                            <input type="text" name="postcode" id="postcode" value="{{ old('postcode') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-postcode-error text-red-700 text-sm block mt-1">
                                @error('postcode')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Suburb -->
                        <div>
                            <label for="suburb" class="block text-sm font-medium text-gray-700 mb-1">Suburb</label>
                            <input type="text" name="suburb" id="suburb" value="{{ old('suburb') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <span class="js-suburb-error text-red-700 text-sm block mt-1">
                                @error('suburb')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>

                        <!-- Profile Image -->
                        <div>
                            <label for="user_image_path" class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                            <input type="file" name="image_path" id='user_image_path' accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <span class="js-image-error text-red-700 text-sm block mt-1">
                                @error('image_path')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script type="module">
        // Wait until the DOM elements are fully painted on screen
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault(); // STOPS the instant form submission immediately!

                    const routes = {
                        login: "{{ route('users.checkLogin') }}",
                        email: "{{ route('users.checkEmail') }}"
                    };

                    // PAUSES right here until all your async checks finish
                    let isValid = await window.userValidation(this, routes);

                    // ONLY submit if your code returns a strict TRUE value
                    if (isValid === true) {
                        this.submit();
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
