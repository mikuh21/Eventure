<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();

        if (! Auth::check() || ! ($currentUser instanceof User) || ! $currentUser->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}