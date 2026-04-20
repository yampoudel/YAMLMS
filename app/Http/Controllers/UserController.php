<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    // Dependency injection from UserService via constructor
    public function __construct(protected UserService $userService) {}

    /**
     * Display a listing of the user.
     */
    public function index(): View
    {
        $users = $this->userService->getUserList(15);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View|RedirectResponse
    {
        // Adding page information for create page
        $page_info = [
            'title' => 'Add New User',
            'back_button' => 'Back To Users',
        ];

        if (Gate::denies('create', User::class)) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to create user.');
        }

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

        $this->userService->storeUser($request->validated());

        // Redirect with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View|RedirectResponse
    {
        // Adding Page Information for edit page
        $page_info = [
            'title' => 'Edit User',
            'back_button' => 'Back To Users',
        ];

        if (Gate::denies('update', $user)) {
            return redirect()->route('users.index')
                ->with('error', 'You are not authorized to update this user.');
        }

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
        $this->userService->updateUser($user, $request->validated());

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
}
