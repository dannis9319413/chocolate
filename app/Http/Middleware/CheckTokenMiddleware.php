<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['status' => 401, 'message' => 'Token未提供'], 401);
        }
        if (!Auth::guard('api')->check()) {
            return response()->json(['status' => 401, 'message' => 'Token驗證失敗'], 401);
        }

        return $next($request);
    }
}
