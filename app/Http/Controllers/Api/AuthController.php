<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //login
    public function login(Request $request)
    {
        //validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        //attempt to login
        $user = User::where ('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        //generate the token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user
        ],200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Token revoked'
        ],200);
    }
}