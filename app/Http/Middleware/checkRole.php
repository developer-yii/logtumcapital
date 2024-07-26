<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            $roleName = User::getRoleName($role);
            foreach ($roles as $value) {
                if($roleName == $value){
                    return $next($request);
                }
            }
            return abort(403);
        }
        return redirect()->to('/');
    }
}
