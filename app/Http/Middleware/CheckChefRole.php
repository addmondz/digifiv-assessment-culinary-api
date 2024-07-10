<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckChefRole
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->role === 'chef') {
            return $next($request);
        }

        return response()->json(['error' => 'Only chef can create recipes'], 403);
    }
}
