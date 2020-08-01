<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Socialite;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirect($provider){
        return Socialite::driver($provider)->redirect();
    }
    public function callback($provider){
        $getInfo = Socialite::driver($provider)->user();
        // dd($getInfo);
        if ($provider == 'github') {
            
            $user = User::where('github_id', $getInfo->id)->first();
            if ($user == null) {
                $user = User::create([
                    'github_token' => $getInfo->token,
                    'name'         => $getInfo->nickname,
                    'email'        => $getInfo->email,
                    'github_id'    => $getInfo->id,
                    'github_data'  => json_encode($getInfo->user),
                ]);
            } else {
                $user->update([
                    'github_token' => $getInfo->token,
                    'github_data'  => json_encode($getInfo->user),
                ]);
            }
            
            Auth::login($user);

        }

        return redirect()->to('/home');
    }
}
