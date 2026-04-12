<x-app-layout>

    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                Users
            </h1>

            <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add User
            </a>

            <a href="{{ route('enrolments.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Enrolment List
            </a>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                {{-- Success Message --}}
                @if (session('success'))
                    <div id='notify_user_created' class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
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
                                <th class="px-4 py-2 border">Country</th>
                                <th class="px-4 py-2 border">City</th>
                                <th class="px-4 py-2 border">Postcode</th>
                                <th class="px-4 py-2 border">Suburb</th>
                                <th class="px-4 py-2 border">Join Date</th>
                                <th class="px-4 py-2 border">Last Login</th>
                                <th class="px-4 py-2 border" colspan="3">Actions</th>
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
                                    <td class="px-4 py-2 border">{{ $user->country }}</td>
                                    <td class="px-4 py-2 border">{{ $user->city }}</td>
                                    <td class="px-4 py-2 border">{{ $user->postcode }}</td>
                                    <td class="px-4 py-2 border">{{ $user->suburb }}</td>
                                    <td class="px-4 py-2 border">{{ $user->join_date }}</td>
                                    <td class="px-4 py-2 border">{{ $user->last_login }}</td>

                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="text-blue-600 hover:underline">
                                            Edit
                                        </a>
                                    </td>

                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('enrolments.create', $user) }}"
                                            class="text-blue-600 hover:underline">
                                            Course Enrol
                                        </a>
                                    </td>

                                    <td class="px-4 py-2 border">
                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 hover:underline">Delete</button>
                                        </form>
                                    </td>
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
                const el = document.getElementById('notify_user_created');
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
