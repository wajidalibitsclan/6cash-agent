<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PreventPagesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName();

        if (Session::has("otp-page") && Session::get('otp-page') == 'can-visit' && $routeName == 'agent.auth.otp') {
            return $next($request);
        } else if (Session::has("reset-page") && Session::get('reset-page') == 'can-visit' && $routeName == 'agent.auth.set.reset.pin') {
            return $next($request);
        } else if (Session::has("information-page") && Session::get('information-page') == 'can-visit' && $routeName == 'agent.information') {
            return $next($request);
        } else if (Session::has("face-page") && Session::get('face-page') == 'can-visit' && $routeName == 'agent.auth.face.verification') {
            return $next($request);
        } else if (Session::has("set-page") && Session::get('set-page') == 'can-visit' && $routeName == 'agent.set.pin') {
            return $next($request);
        } else {
            abort(403, 'Access Denied');
        }
        // return $next($request);
    }
}
