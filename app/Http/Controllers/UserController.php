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
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,Farm-Owner,Farm-Worker',
            'contactNumber' => 'nullable|string|max:255',
            'dateOfBirth' => 'nullable|date',
            'province' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
        ]);

        try {
            // Update only the allowed fields
            $user->update($request->only([
                'firstName',
                'lastName',
                'email',
                'role',
                'contactNumber',
                'dateOfBirth',
                'province',
                'municipality',
                'barangay',
            ]));

            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating user: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8', // Add password validation
            'role' => 'required|in:Admin,Farm-Owner,Farm-Worker',
            'contactNumber' => 'nullable|string|max:255',
            'dateOfBirth' => 'nullable|date',
            'province' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
        ]);

        try {
            // Use the CreateNewUser action to create a new user
            app(CreateNewUser::class)->create($request->all());

            return response()->json(['message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating user: ' . $e->getMessage()], 500);
        }
    }
}
