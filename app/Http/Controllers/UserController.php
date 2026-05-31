<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\EmailService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    // Dependency injection from UserService,EmailService via constructor
    public function __construct(protected UserService $userService, protected EmailService $emailService) {}

    /**
     * Display a listing of the user.
     */
    public function index(): View
    {   // Check policy
        $this->authorize('viewAny', User::class);

        // Get all users
        $users = $this->userService->getUserList(15);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View|RedirectResponse
    {
        // Check Policy
        if (Gate::denies('create', User::class)) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to create user.');
        }

        // Adding page information for create page
        $page_info = [
            'title' => 'Add New User',
            'back_button' => 'Back To Users',
        ];

        return view('admin.user.create', compact('page_info'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        if (Gate::denies('create', User::class)) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to create user.');
        }

        // Capture result 'user' and plain password
        $result = $this->userService->storeUser($request->validated(), $request->file('image_path'));

        // Send Email using plain password
        $this->emailService->sendWelcomeEmail($result['user'], $result['password']);

        // Redirect with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View|RedirectResponse
    {
        // Check policy
        if (Gate::denies('update', $user)) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to update this user.');
        }

        // Adding Page Information for edit page
        $page_info = [
            'title' => 'Edit User',
            'back_button' => 'Back To Users',
        ];

        return view('admin.user.edit', compact(['user', 'page_info']));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if (Gate::denies('update', $user)) {
            return redirect()->route('users.index', $user)
                ->with('error', 'You are not authorized to update this user.');
        }

        // Update user
        $this->userService->updateUser($user, $request->validated(), $request->file('image_path'));

        return redirect()->route('users.index', $user)->with('success', 'User details has been updated successfully. ');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        if (Gate::denies('delete', $user)) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to delete this user.');
        }

        try {
            $this->userService->deleteUser($user);

            return redirect()->route('users.index')->with('success', 'User has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Check unique login
     */
    public function checkLoginUnique(Request $request)
    {
        // Automatically captures your 'login' value from the URL query string
        $login_value = $request->input('login');

        if (! $login_value) {
            return response()->json(['isUnique' => true]);
        }

        // Query the table safely
        $exists = User::where('login', $login_value)->exists();

        return response()->json([
            'isUnique' => ! $exists,
        ]);
    }

    /**
     * Check unique login
     */
    public function checkEmailUnique(Request $request)
    {
        // Automatically captures your 'email' value from the URL query string
        $email_value = $request->input('email');

        if (! $email_value) {
            return response()->json(['isUnique' => true]);
        }

        // Query the table safely
        $exists = User::where('email', $email_value)->exists();

        return response()->json([
            'isUnique' => ! $exists,
        ]);
    }
}
