<?php

namespace Crater\Http\Middleware;

use Closure;
use Request;
use Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest() || !Auth::user()->isCustomer()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return response()->json(['error' => 'user_is_not_customer'], 404);
            }
        }
        return $next($request);
    }
}