<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserController extends Controller
{
    //list users
    public function index() : View {
        
        $users = User::paginate(15);
        
        return view('admin.user.index', compact('users'));
    }

    public function create (): View {
        //Adding Page Information
        $page_info = [];
        $page_info['title']= 'ADD A NEW USER';

        return view ('admin.user.create', compact('page_info'));
    }

    public function store(Request $request) : RedirectResponse {
    
        $validated_users = $request->validate([
            'role'       => ['required', Rule::in(['Admin', 'Learner', 'Teacher'])],
            'login'      => 'required|string|max:255|unique:lms_users,login', // Assumes 'users' table
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:lms_users,email', // Added unique check for email too
            'password'   => ['required', Password::min(8)],
            'status'     => ['required', Rule::in(['Active', 'Disabled'])],
            'birth_date' => 'required|date',
            'phone'      => 'required|string|max:255',
            'mobile'     => 'required|string|max:255',
            'country'    => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'postcode'   => 'required|string|max:255',
            'suburb'     => 'required|string|max:255',
        ]);

        //Hash the password before saving!
        $validated_users['password'] = Hash::make($validated_users['password']);

        //Deafult value
        $validated_users['join_date'] = now();
        $validated_users['last_login'] = null;

        // Create the user in the database
        User::create($validated_users);

        //Redirect with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');

    }

    // Edit user page
    public function edit (string $id) : View {
       //Get user data
        $user_details = User::find($id);

        return view('admin.user.edituser', compact('user_details'));
    }

}
