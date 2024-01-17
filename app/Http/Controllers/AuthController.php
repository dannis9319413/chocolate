<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->api_token_expires_at = now()->addDays(1);
            $user->save();
            return response()->json(['token' => $user->api_token], 200);
        }

        return response()->json(['status' => 401, 'message' => 'Unauthorized'], 401);
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['status' => 201, 'message' => 'Logged out successfully'], 201);
        }

        return response()->json(['status' => 401, 'message' => 'Not logged in'], 401);
    }
}
