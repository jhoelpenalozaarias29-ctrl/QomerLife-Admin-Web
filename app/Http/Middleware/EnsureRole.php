<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = Auth::user();

        if (! $user || ! isset($user->role)) {
            return redirect()->route('admin.login');
        }

        $flatRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $part) {
                $trimmed = strtoupper(trim($part));
                if ($trimmed !== '') {
                    $flatRoles[] = $trimmed;
                }
            }
        }

        if (! in_array(strtoupper($user->role), $flatRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
