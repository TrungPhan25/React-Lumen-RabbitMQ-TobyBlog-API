<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleBaseProviderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = Auth::guard($guard)->user();
        // $provider = $user->role == 3 ? 'users' : 'admins';
        if ($user->role == 3) {
            $url_name             = $request->route()[1]['as'];
            $data_urls_only_admin = config('constants.route_only_admin');
            $check_urls           = in_array($url_name, $data_urls_only_admin);

            if ($check_urls) {
                return response('Unauthorized.', 401);
            }            
        }

        return $next($request);
    }
}
