<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckOwnership
{
    public function handle($request, Closure $next)
    {
        $book = $request->route('book');
        $user = Auth::user();
    
        if ($user->role == 'admin') {
            // Jika user adalah admin, berikan akses tanpa batasan
            return $next($request);
        }
    
        return $next($request);
    }
    
}
