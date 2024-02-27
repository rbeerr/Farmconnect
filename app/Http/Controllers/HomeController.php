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
    
            // Redirect based on user role
            switch ($role) {
                case 'Admin':
                    $users = \App\Models\User::paginate(10); // Pass the users data to the view
                    return view('admin.dashboard', compact('users')); 
                case 'Farm-Owner':
                case 'Farm-Worker':
                    $users = \App\Models\User::paginate(10);
                    return view('dashboard', compact('users'));
                default:
                    // Handle unknown role or other cases
                    return redirect()->route('login');
            }
        } else {
            // User is not authenticated, handle accordingly (redirect to login page, show a message, etc.)
            return redirect()->route('login');
        }
    }
    
}
