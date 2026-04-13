<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Course;
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
    public function create (): View 
    {
        //Adding Page Information
        $page_info = [];
        $page_info['title']= 'ADD A NEW USER';
        
        return view ('admin.user.create', compact('page_info'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request) : RedirectResponse 
    {
        $this->userService->storeUser($request->validated());
        
        //Redirect with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit (User $user) : View 
    {
       return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user) : RedirectResponse 
    {
        //Update user
        $this->userService->updateUser($user, $request->validated());
        
        return redirect()->route('users.edit', $user->id)->with('success', 'User details has been updated successfully. ');
    }

    /**
     * Remove the specified course from storage
     */
    public function destroy(User $user) // Laravel injects this as $user
    {   
        // Pass the $user variable into the authorize check
        $this->authorize('delete', $user); 

        try {
            $this->userService->deleteUser($user);
            return redirect()->route('users.index')->with('success', 'User has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }
}
