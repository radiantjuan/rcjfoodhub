<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckIfModerator
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
        $user = Auth::user();
        $role_name = Role::find_role_by_id($user->role_id);
        if($role_name !== 'admin' && $role_name !== 'branch') {
            return redirect(route('supplies.index'));
        }
        return $next($request);
    }
}
