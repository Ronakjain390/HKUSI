<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckAipMember
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
        $user = $request->user();
        if (isset($user->status) && $user->status == '0') {
            Auth::user()->tokens()->delete();
        }
        if (isset($user->getMemberInfo->status) && $user->getMemberInfo->status == '0') {
            Auth::user()->tokens()->delete();
        }
        return $next($request);
    }
}
