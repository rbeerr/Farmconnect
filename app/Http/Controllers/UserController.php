<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function update(Request $request, User $user)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:0,1', // Assuming 0 is Default User and 1 is Admin
        ]);

        // Update user information, including the 'role' attribute
        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error updating user: ' . $e->getMessage()], 500);
    }
}


}
