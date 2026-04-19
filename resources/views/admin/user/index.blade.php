<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <!-- SECTION 1: Extreme Left (The Button) -->
            <div class="flex-1 flex justify-start gap-4">
                @can('create', App\Models\User::class)
                    <a href="{{ route('users.create') }}"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                        <span class="mr-2">+</span> Add User
                    </a>
                @endcan

                <a href="{{ route('enrolments.index') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-full shadow-sm hover:bg-blue-700 transition-all duration-200">
                    Enrolment List
                </a>
            </div>

            <!-- SECTION 2: Center (The Title) -->
            <div class="flex-1 text-center">
                <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                    Users
                </h1>
            </div>

            <!-- SECTION 3: Right Spacer (Keeps the title perfectly in the middle) -->
            <div class="flex-1"></div>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                @if (session('success') || session('error'))
                    <div
                        class="notify_message mb-4 p-3 border rounded {{ session('success') ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                        {{ session('success') ?? session('error') }}
                    </div>
                @endif

                <div class="bg-white shadow-sm sm:rounded-lg p-4 overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">S.N.</th>
                                <th class="px-4 py-2 border">First Name</th>
                                <th class="px-4 py-2 border">Last Name</th>
                                <th class="px-4 py-2 border">Role</th>
                                <th class="px-4 py-2 border">Login</th>
                                <th class="px-4 py-2 border">Email</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Join Date</th>
                                <th class="px-4 py-2 border">Last Login</th>

                                @if (auth()->user()->isAdmin() || auth()->user()->role === 'Teacher')
                                    <th class="px-4 py-2 border" colspan="3">Actions</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $users->firstItem() + $index }}</td>
                                    <td class="px-4 py-2 border">{{ $user->first_name }}</td>
                                    <td class="px-4 py-2 border">{{ $user->last_name }}</td>
                                    <td class="px-4 py-2 border">{{ $user->role }}</td>
                                    <td class="px-4 py-2 border">{{ $user->login }}</td>
                                    <td class="px-4 py-2 border">{{ $user->email }}</td>
                                    <td class="px-4 py-2 border">{{ $user->status }}</td>
                                    <td class="px-4 py-2 border">{{ $user->join_date }}</td>
                                    <td class="px-4 py-2 border">{{ $user->last_login }}</td>

                                    @can('update', $user)
                                        <td class="px-4 py-2 border">
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="text-blue-600 hover:underline">
                                                Edit
                                            </a>
                                        </td>
                                    @endcan


                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('enrolments.create', $user) }}"
                                            class="text-blue-600 hover:underline">
                                            Course Enrol
                                        </a>
                                    </td>

                                    @can('delete', $user)
                                        <td class="px-4 py-2 border">
                                            <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                // Select all messages in case there are multiple
                const messages = document.querySelectorAll('.notify_message');

                messages.forEach(el => {
                    el.style.transition = "opacity 0.8s ease";
                    el.style.opacity = "0";

                    setTimeout(() => {
                        el.remove();
                    }, 800);
                });
            }, 3000);
        </script>
    @endpush
</x-app-layout>
