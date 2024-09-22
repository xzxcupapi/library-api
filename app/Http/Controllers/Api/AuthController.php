<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|max:255|unique:users',
    //         'password' => 'required|string|min:8',
    //         'no_hp' => 'required|string|max:15',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors());
    //     }

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'no_hp' => $request->no_hp
    //     ]);

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'data' => $user,
    //         'access_token' => $token,
    //         'token_type' => 'Bearer'
    //     ]);
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not registered.'
            ], 404);
        }

        // Cek apakah password salah
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid password.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'User not authenticated.'
            ], 401);
        }

        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
