<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SocialMiddleware
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
        $services = ['facebook', 'twitter', 'linkedin', 'google', 'github', 'gitlab', 'bitbucket'];
        $enaledServices = [];

        foreach($services as $service)
        {
            if(config('services'.$service))
            {
                $enaledServices[] = $service;
            }
        }

        if(!in_array(strtolower($request->service), $enaledServices))
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid social service'
                ], 403);
            }
            else
            {
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
