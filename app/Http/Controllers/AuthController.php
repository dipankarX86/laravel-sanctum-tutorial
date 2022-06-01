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
}
