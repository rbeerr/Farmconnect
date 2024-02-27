<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Dashboard route based on user role
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Route for handling redirects
    Route::get('redirects', [HomeController::class, 'index']);

    // Admin dashboard route
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');

    // User management routes
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Add User route
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

// Redirect from root URL to the login page
Route::view('/', 'auth.login')->name('root');
