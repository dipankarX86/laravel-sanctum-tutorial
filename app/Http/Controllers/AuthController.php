<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;  // need to create custom response
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        // did we create users before? not in this project, here only products were worked on
        // but why auth controller, no user controller?
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        // create the api token
        $token = $user->createToken('myAppToken')->plainTextToken;

        // response
        $response = [
            'user' => $user,
            'token' => $token
        ];

        // return what is needed
        return response($response, 201);
    }


    // Login using api
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check if the email is present
        $user = User::where('email', $fields['email'])->first();

        // Check Password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad Creds'
            ], 401);
        }

        // create the api token
        $token = $user->createToken('myAppToken')->plainTextToken;

        // response
        $response = [
            'user' => $user,
            'token' => $token
        ];

        // return what is needed
        return response($response, 201);
    }


    // Logout function
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];
    }
}
