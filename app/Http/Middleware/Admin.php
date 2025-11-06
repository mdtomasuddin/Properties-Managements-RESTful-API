<?php

namespace App\Http\Middleware;

use App\Traits\V1\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is admin
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }
        
        return redirect()->route('login')->withErrors(['email' => 'Unauthorized access. Admin only.']);
    }
}
