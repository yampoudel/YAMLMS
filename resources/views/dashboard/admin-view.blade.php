{{-- Statistics Cards Grid --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Users -->
    <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center">
        <div class="p-3 bg-blue-50 rounded-lg mr-4 text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Users</p>
            <h4 class="text-2xl font-extrabold text-gray-900">{{ $total_users ?? 0 }}</h4>
        </div>
    </div>

    <!-- Total Courses -->
    <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center">
        <div class="p-3 bg-green-50 rounded-lg mr-4 text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Courses</p>
            <h4 class="text-2xl font-extrabold text-gray-900">{{ $total_courses ?? 0 }}</h4>
        </div>
    </div>

    <!-- Total Enrolments -->
    <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center">
        <div class="p-3 bg-purple-50 rounded-lg mr-4 text-purple-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Enrolments</p>
            <h4 class="text-2xl font-extrabold text-gray-900">{{ $total_enrolments ?? 0 }}</h4>
        </div>
    </div>
</div>

{{-- Recent Users Table Section --}}
<div class="bg-white shadow-sm rounded-xl border border-gray-200 w-full mt-8 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/30">
        <h3 class="text-lg font-bold text-gray-800">Recently Joined Users</h3>
    </div>
    
    <div class="overflow-x-auto">
        {{-- table-auto lets columns fit content; w-full expands the container --}}
        <table class="w-full text-left table-auto border-collapse">
            <thead>
                <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">
                    <th class="px-6 py-3 border-b border-gray-200">Name</th>
                    <th class="px-6 py-3 border-b border-gray-200">Role</th>
                    <th class="px-6 py-3 border-b border-gray-200">Joined</th>
                    {{-- This "Ghost" column pushes the others to the left --}}
                    <th class="px-6 py-3 border-b border-gray-200 w-full"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recent_users as $recent_user)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                            {{ $recent_user->first_name }} {{ $recent_user->last_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter 
                                {{ $recent_user->isAdmin() ? 'bg-purple-100 text-purple-700' : 
                                   ($recent_user->isTeacher() ? 'bg-green-100 text-green-700' : 
                                   'bg-blue-100 text-blue-700') }}">
                                {{ $recent_user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                            {{ $recent_user->created_at->diffForHumans() }}
                        </td>
                        {{-- Matching empty cell for the ghost column --}}
                        <td class="px-6 py-4"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic font-medium bg-white">
                            No users have joined the platform yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
