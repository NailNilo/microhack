<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //login 
    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = auth()->user();

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    //register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        $random_password = fake()->password(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($random_password),
        ]);


        return response()->json([
            'user' => $user,
            'password' => $random_password,
        ]);
    }

    //show user
    public function ShowUserDocs(Request $request)
    {
        $user_id = $request->route('user_id');

        $user = User::findOrFail($user_id);

        return response()->json([
            'user' => new UserResource($user) ,
        ]);
    }

    public function Show(Request $request)
    {
        
        $user = auth()->user();

        return response()->json([
            'user' => new UserResource($user) ,
        ]);
    }

}
