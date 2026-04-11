<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Course;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource/user.
     */
    public function index() : View 
    {
        $users = User::paginate(15);
      
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource/user
     */
    public function create (): View 
    {
        //Adding Page Information
        $page_info = [];
        $page_info['title']= 'ADD A NEW USER';
        
        return view ('admin.user.create', compact('page_info'));
    }

    /**
     * Store a newly created resource/user in storage.
     */
    public function store(StoreUserRequest $request) : RedirectResponse 
    {
        $validated_user = $request->validated();

        //Hash the password before saving!
        $validated_user['password'] = Hash::make($validated_user['password']);

        //Deafult value
        $validated_user['join_date'] = now();
        $validated_user['last_login'] = null;

        // Create the user in the database
        User::create($validated_user);

        //Redirect with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource/user.
     */
    public function edit (string $id) : View 
    {
       //Get user data
        $user = User::findOrfail($id);

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource/user in storage.
     */
    public function update(UpdateUserRequest $request, User $user) : RedirectResponse 
    {
        //Update user
        $user->update($request->validated());
        
        return redirect()->route('users.edit', $user->id)->with('success', 'User details has been updated successfully. ');
    }

    //Deleteing user
    public function destroy(User $user) : RedirectResponse 
    {
        //Will add other checkes in future when needed
        //Like not deleted by self, only authorized user can delete etc
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User has been deleted successfully');
    }
}
