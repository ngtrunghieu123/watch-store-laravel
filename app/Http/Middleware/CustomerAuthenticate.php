<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            // Chuyển về trang đăng nhập frontend
            return redirect()->route('frontend.dangnhap');
        }

        return $next($request);
    }
}
