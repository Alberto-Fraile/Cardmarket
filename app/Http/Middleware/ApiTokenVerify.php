<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApiTokenVerify
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
        $apitoken = $request->api_token;
        Log::info('Search ApiToken'); 
        $user = User::where('api_token', $apitoken)->first();

        if(!$user) {
            $request['status'] = 0;
            $request['msg'] = "An error has occurred: ";  
            Log::error('An error has occurred: '); 

        }else{
            $request->user = $user;
            return $next($request);
        }
        return response()->json($request);
    }
}
