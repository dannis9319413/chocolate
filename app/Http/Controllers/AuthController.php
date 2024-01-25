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
            return response()->json(['status' => 200, 'message' => '登入成功', 'token' => $user->api_token], 200);
        }

        return response()->json(['status' => 401, 'message' => 'email或密碼錯誤'], 401);
    }
    public function logout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 401, 'message' => '找不到使用者'], 401);
        }

        $request->user()->forceFill(['api_token' => null])->forceFill(['api_token_expires_at' => null])->save();
        return response()->json(['status' => 201, 'message' => '登出成功'], 201);
    }
}
