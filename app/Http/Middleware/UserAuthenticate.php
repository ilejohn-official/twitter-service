<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAuthenticate
{

    public const AUTH_USER_IDS = [
        '80000000-8000-8000-8000-000000000008',
        '80000008-8008-8008-8008-800000000008',
        '90000000-9000-9000-9000-000000000009',
        '90000009-9009-9009-9009-900000000009'
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty($request->header('user-id'))){
            return response('Unauthorized.', 401);
        }

        if (!in_array($request->header('user-id'), self::AUTH_USER_IDS)){
            return response('Invalid User Id.', 401);
        }

        return $next($request);
    }
}
