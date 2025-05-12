<?php

namespace App\Http\Middleware;

use App\Actions\ResponseAction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiGuardAuthCheckMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('api')->check()) {
            return ResponseAction::error('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
