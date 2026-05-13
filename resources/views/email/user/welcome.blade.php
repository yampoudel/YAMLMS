<x-mail::message>
# Welcome to {{ config('app.name') }}, {{ $user->name }}!

An administrator has created an account for you. You can now log in to start your training journey.

**Your Login Credentials:**
*   **Email:** {{ $user->email }}
*   **Temporary Password:** `{{ $password }}`

<x-mail::button :url="route('login')">
Log In to Portal
</x-mail::button>

*Please change your password immediately after your first login.*

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
