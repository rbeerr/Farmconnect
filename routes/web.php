<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Actions\Fortify\CreateNewUser;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');});

// Redirect from root URL to the login page
Route::view('/', 'auth.login')->name('root');

Route::get('redirects', [HomeController::class, 'index']);
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

