<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    //Dependency Injection from UserService via consturctor
    public function __construct(protected UserService $userService) {}

    /**
     * Display a listing of the user.
     */
    public function index() : View 
    {
        $users = $this->userService->getAllUsers(15);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View | RedirectResponse
    {
        //Adding Page Information
        $page_info = [];
        $page_info['title']= 'ADD A NEW USER';

        if (Gate::denies('create', User::class)) {
            return redirect()->route('users.index')
                             ->with('error', 'You are not authorized to create user.');
        }
        return view ('admin.user.create', compact('page_info'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request) : RedirectResponse 
    {
        if (Gate::denies('create', User::class)) {
            return redirect()->route('users.index')
                             ->with('error', 'You are not authorized to create user.');
        }

        $this->userService->storeUser($request->validated());
        
        //Redirect with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user) : View | RedirectResponse
    {
        if (Gate::denies('update', $user)) {
            return redirect()->route('users.index')
                             ->with('error', 'You are not authorized to update this user.');
        }
        
       return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user) : RedirectResponse 
    {   
        if (Gate::denies('update', $user)) {
            return redirect()->route('users.index', $user)
                             ->with('error', 'You are not authorized to update this user.');
        }

        //Update user
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
