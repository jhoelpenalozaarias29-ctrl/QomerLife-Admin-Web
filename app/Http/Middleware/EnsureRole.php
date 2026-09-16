<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = Auth::user();

        if (! $user || ! isset($user->role)) {
            return redirect()->route('admin.login');
        }

        $allowed = explode(',', $roles);
        if (! in_array(strtoupper($user->role), array_map('trim', array_map('strtoupper', $allowed)), true)) {
            abort(403);
        }

        return $next($request);
    }
}
