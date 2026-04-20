<!-- Added flex flex-col to make mt-auto work -->
<aside class="w-80 flex-shrink-0 min-h-screen bg-white dark:bg-gray-800 border-r border-gray-200 flex flex-col">

    <!-- Top Section (Logo & Nav) -->
    <div class="flex-1">
        <!-- APPLICATION LOGO AREA -->
        <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="ml-3 text-lg font-bold text-gray-800 dark:text-white tracking-tight">LMS Admin</span>
            </a>
        </div>

        <nav class="py-4 px-3 space-y-2">
            <!-- User Management -->
            <a href="{{ route('users.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group
                    {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('users.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                    xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ __('USER MANAGEMENT') }}
            </a>

            <!-- Course Management -->
            <a href="{{ route('courses.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group
                    {{ request()->routeIs('courses.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('courses.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                    xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
                {{ __('COURSE MANAGEMENT') }}
            </a>

            <!-- Enrolments -->
            <a href ="{{ route('enrolments.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group
                    {{ request()->routeIs('enrolments.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('enrolments.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                    xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ __('ENROLMENTS') }}
            </a>

            <!-- Lessons Management -->
            <a href ="{{ route('lessons.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors group
                    {{ request()->routeIs('lessons.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('lessons.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                    xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ __('LESSON MANAGEMENT') }}
            </a>
        </nav>
    </div>

    <!-- Bottom Section: Logout -->
    <!-- mt-auto pushes this to the bottom -->
    <div class="mt-auto pb-6 px-3 border-t border-gray-100 dark:border-gray-700 pt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center w-full px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors group">
                <svg class="w-5 h-5 mr-3 text-red-400 group-hover:text-red-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                {{ __('LOG OUT') }}
            </button>
        </form>
    </div>
</aside>
