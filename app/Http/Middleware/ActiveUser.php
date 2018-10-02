<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!$user->active) {
            Auth::logout();

            if (is_null($user->desk)) {
                return redirect()->route('admin.login');
            }

            return redirect()->route('collector.login');
        }

        return $next($request);
    }
}
