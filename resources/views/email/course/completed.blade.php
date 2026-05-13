<x-mail::message>
# Dear {{ $user->name }}, 

Congratulations! You have successfully completed the course **{{ $course->title }}**. 

You can now download your certificate by logging in to your student portal.

<x-mail::button :url="route('login')">
Log In to Portal
</x-mail::button>

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
