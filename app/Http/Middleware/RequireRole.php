<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowed = array_map(
            fn (string $r) => UserRole::from($r),
            $roles
        );

        if (! $user->hasRole(...$allowed)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
