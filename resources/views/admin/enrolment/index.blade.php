<x-app-layout>

    {{-- Header Section --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                User Enrolments
            </h1>

            <a href="{{ route('users.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Users List
            </a>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-6">
        <div class="w-full px-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                {{-- Success Message --}}
                @if (session('success'))
                    <div id='notify_enrol_created' class="mb-4 p-3 bg-green-100 text-green-700 rounded">
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
                                <th class="px-4 py-2 border">Course Title</th>
                                <th class="px-4 py-2 border">Enroled Date</th>
                                <th class="px-4 py-2 border" colspan="2">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($enrolments as $index => $enrolment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border">{{ $enrolment->user->first_name }}</td>
                                    <td class="px-4 py-2 border">{{ $enrolment->user->last_name }}</td>
                                    <td class="px-4 py-2 border">{{ $enrolment->course->title }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ date('Y-m-d', strtotime($enrolment->enrolled_at)) }}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('enrolments.edit', $enrolment->id) }}"
                                            class="text-blue-600 hover:underline">
                                            Edit
                                        </a>
                                    </td>

                                    <td class="px-4 py-2 border">
                                        <form action="{{ route('enrolments.destroy', $enrolment) }}" method="POST">
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
                    <div class='mt-4'>
                        {{ $enrolments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                const el = document.getElementById('notify_enrolment_created');
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
