<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ActiveCollector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $collector = $request->user();

        if (! is_null($collector->date_terminated)) {
            Auth::logout();

            return redirect()->route('collector.login');
        }

        return $next($request);
    }
}
