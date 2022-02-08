<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\JWTAuth;
use Laravel\Socialite\Two\InvalidStateException;
use App\Models\User;
use App\Models\UserSocial;

class SocialLoginController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
        $this->middleware('social');
    }

    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }

    public function callback($service)
    {
        try{

        }catch (InvalidStateException $e){
            return redirect(env('CLIENT_BASE_URL') . '/auth/social-callback?error=Unable to login using '. $service . '. Please try again.');
        } 

        $serviceUser = Socialite::driver($service)->stateless()->user();

        $email = $serviceUser->getEmail();
        if($service != 'google'){
            $email = $serviceUser->getId() . '@' . $service . '.local';
        }

        $user = $this->getExistingUser($serviceUser, $email, $service);
        if(!$user)
        {
            $user = User::create([
                'name' => $serviceUser->getName(),
                'email' => $email,
                'password' => ''
            ]);
        }

        if($this->needsToCreateSocial($user, $service)){
            UserSocial::create([
                'user_id'   => $user->id,
                'social_id' => $serviceUser->getId(),
                'service'   => $service
            ]);
        }

        return redirect(env('CLIENT_BASE_URL') . '/auth/social-callback?token=' . $this->auth->fromUser($user));

    }

    public function needsToCreateSocial(User $user, $service)
    {
        // From modal
        return !$user->hasSocialLinked($service);
    }

    public function getExistingUser($serviceUser, $email, $service)
    {
        if($service == 'google')
        {
            return User::where('email', $email)
                        ->orWhereHas('social', function($q) use ($serviceUser, $service) {
                                $q->where('social_id', $serviceUser->getId())
                                  ->where('service', $service);
            })->first();
        }
        else
        {
            $userSocial = UserSocial::where('social_id', $serviceUser->getId())->first();
            return $userSocial ? $userSocial->user : null;
        }
    }
}
