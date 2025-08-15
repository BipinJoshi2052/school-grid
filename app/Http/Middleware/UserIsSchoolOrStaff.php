<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIsSchoolOrStaff
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
        if (auth()->check() && in_array(auth()->user()->user_type_id, [2, 3])) {
            return $next($request);
        }
        // Redirect or show error if the user does not have the correct user_type_id
        return redirect()->route('unauthorized'); // Define the unauthorized route
    }
}
