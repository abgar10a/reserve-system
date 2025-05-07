<?php

namespace App\Http\Middleware;

use App\Actions\ResponseAction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if ($request->user()->role !== $role) {
            return ResponseAction::error('Permission denied', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
