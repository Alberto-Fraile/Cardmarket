<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserValidation
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
        Log::info('Search rol'); 
        if($request->user->rol == 'particular' || $request->user->rol == 'professional'){

            return $next($request);
        }else {
            $request['status'] = 0;
            $request['msg'] = "You don't have permissions for doing this ";
            Log::error('An error has occurred: '); 
        }
        return response()->json($request);
    }
}
