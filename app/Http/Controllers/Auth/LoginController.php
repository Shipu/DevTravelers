<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param $service
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($service)
    {
        return Socialite::driver($service)->redirect();
    }

    /**
     * Obtain the user information from Social.
     *
     * @param $service
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback($service)
    {
        $user = Socialite::driver($service)->user();

        return $this->saveUser($user);
    }

    protected function saveUser($response)
    {
        $email = $response->getEmail();
        $name = $response->getName();
        $password = Hash::make(snake_case($response->getName()).uniqid());

        if(!blank($email)) {
            $user = BackpackUser::where('email', $email)->first();
        } else {
            \Alert::error("Please try another login method !!!")->flash();
            return redirect()->back();
        }

        if(blank($user)) {
            $user =  BackpackUser::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'avatar' => $response->getAvatar()
            ]);
        }

        if(backpack_auth()->login($user)) {
            return redirect('/');
        }

        return redirect('/');
    }
}
