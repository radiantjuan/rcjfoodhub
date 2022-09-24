<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //TODO CHECK IF THE USER IS ADMIN OR FRANCHISEE THEN REDIRECT THEM TO THE RIGHT WEBSITE
        $user = Auth::user();
        $role_name = Role::find_role_by_id($user->role_id);
        if($role_name !== 'admin' && $role_name !== 'moderator') {
            return redirect(route('franchise.orders.index'));
        }

        return $next($request);
    }
}
