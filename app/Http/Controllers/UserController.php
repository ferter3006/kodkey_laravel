<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createuser(Request $request)
    {
        // Validate data
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required',
        ]);

        // Create this new User
        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        return [
            'status' => 1,
            'user' => $newUser->only('id', 'name', 'email')
        ];
    }

    public function loginuser(Request $request)
    {
        // Attemp to Login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return [
                'status' => 0,
                'message' => 'Invalid Login details'
            ];
        }

        // Get info from User and create Token
        $thisUser = $request->user();
        $token = $thisUser->createToken('token')->plainTextToken;

        return [
            'status' => 1,
            'token' => $token,
            'user' => $thisUser->only('id', 'name', 'email'),
        ];
    }
}
