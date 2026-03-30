<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class UserController extends Controller
{
    //list users
    public function index() : View {
        
        $users = User::all();
        
        return view('admin.user.listusers', compact('users'));
    }

    // Edit user page
    public function show (string $id) : View {
       //Get user data
        //$user_details = User::where('id', (int)$id)->first();

        $user_details = User::find($id);
       
        return view('admin.user.edituser', compact('user_details'));
    }
}
