<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exception;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        try
        {
            if(!$token = $this->auth->attempt($request->only('email', 'password')))
            {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'email' => 'Invalid emal address or password!'
                    ]
                ], 422);
            }

        }
        catch(JWTException $e)
        {
            return response()->json([
                'success' => false,
                'errors' => [
                    'email' => 'Invalid emal address or password!'
                ]
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $request->user(),
            'token' => $token
        ], 200);
        // $request->authenticate();

        // $request->session()->regenerate();

        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
