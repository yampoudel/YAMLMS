<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 text-center">
                EDIT AN USER
            </h1>
        </div>
    </x-slot>

    {{-- Main Content --}}

    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- Success Message --}}
                @if (session('success'))
                    <div id='notify_user_updated' class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="role"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="Admin"{{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin
                                </option>
                                <option value="Learner"{{ old('role', $user->role) == 'Learner' ? 'selected' : '' }}>
                                    Learner
                                </option>
                                <option value="Teacher"{{ old('role', $user->role) == 'Teacher' ? 'selected' : '' }}>
                                    Teacher
                                </option>
                            </select>
                            @error('role')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Login -->
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Login</label>
                            <input type="text" name="login" id="login" value="{{ old('login', $user->login) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('login')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First
                                Name</label>
                            <input type="text" name="first_name" id="first_name"
                                value ="{{ old('first_name', $user->first_name) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('first_name')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last
                                Name</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ old('last_name', $user->last_name) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('last_name')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="text" name="email" id="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('email')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                id="status"class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="Active"{{ old('status', $user->status) == 'Active' ? 'selected' : '' }}>
                                    Active</option>
                                <option
                                    value="Disabled"{{ old('status', $user->status) == 'Disabled' ? 'selected' : '' }}>
                                    Disabled</option>
                            </select>
                            @error('status')
                                <span class="text-red-700">
                                    {{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Birthdate -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Date of
                                Birth</label>
                            <input type="date" name="birth_date" id="birth_date"
                                value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('birth_date')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('phone')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                            <input type="text" name="mobile" id="mobile"
                                value="{{ old('login', $user->mobile) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('mobile')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" id="country"
                                value="{{ old('login', $user->country) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('country')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" id="city" value="{{ old('login', $user->city) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('city')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Postcode -->
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-gray-700 mb-1">Post
                                Code</label>
                            <input type="text" name="postcode" id="postcode"
                                value="{{ old('postcode', $user->postcode) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('postcode')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Suburb -->
                        <div>
                            <label for="suburb" class="block text-sm font-medium text-gray-700 mb-1">Suburb</label>
                            <input type="text" name="suburb" id="suburb"
                                value="{{ old('login', $user->suburb) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            @error('suburb')
                                <span class="text-red-700">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Submit -->
                    <div class="mt-8 text-center">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                const el = document.getElementById('notify_user_updated');
                if (el) {
                    el.style.transition = "opacity 0.8s ease";
                    el.style.opacity = "0";

                    setTimeout(() => {
                        el.remove();
                    }, 800); // match transition duration
                }
            }, 3000); // wait 5 seconds before fading
        </script>
    @endpush
</x-app-layout>
