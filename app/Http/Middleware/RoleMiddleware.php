<?php

namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
 
        $user = Auth::user();
 
        if (! $user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Tu cuenta está desactivada.']);
        }
 
        if ($user->role->slug !== $role) {
            abort(403, 'No tienes permisos para esta sección.');
        }
 
        return $next($request);
    }
}