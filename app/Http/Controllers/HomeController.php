<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role == '1') {
                $users = \App\Models\User::paginate(10);
                return view('admin.dashboard', compact('users'));
            } else {
                $users = \App\Models\User::paginate(10);
                return view('dashboard', compact('users'));
            }
        } else {
            // User is not authenticated, handle accordingly (redirect to login page, show a message, etc.)
            return redirect()->route('login'); // Redirect to the login page, replace with your desired logic
        }
    }

}

