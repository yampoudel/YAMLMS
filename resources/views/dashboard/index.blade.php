<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   <!-- If login user is and admin -->
                   @if (auth()->user()->isAdmin())
                        @include('dashboard.admin-view')
                    <!-- If login user is a teacher -->
                    @elseif (auth()->user()->isTeacher())
                        @include('dashboard.teacher-view')
                    <!-- If login user is a learner -->
                     @elseif (auth()->user()->isLearner())
                        @include('dashboard.learner-view')
                   @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
