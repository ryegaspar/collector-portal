<?php

namespace App\Http\Middleware;

use Closure;

class CollectorResetPassword
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

        if (is_null($user->change_pass_at)) {
            return redirect()->route('collector.collector-reset-password');
        }

        return $next($request);
    }
}
