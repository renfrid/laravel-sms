<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //login form
    function showLoginForm()
    {
        $title = 'Login';

        //check if user already login
        if (Auth::check()) {
            $user = User::find(Auth::user()->id);

            Auth::logout();
            return Redirect::route('login')->with('danger', 'Your account is deactivated, contact with administrator.');
        }

        //render view
        return view('auth.login', compact('title'));
    }

    //authenticate
    public function login(Request $request)
    {
        //validation
        $this->validate(
            $request,
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email required',
                'password.required' => 'Password required',
            ]
        );

        // //post data
        $email = $request->input('email');
        $password = $request->input('password');
        $remember_me = $request->has('remember_me') ? true : false;

        // //check remember me function
        // if ($request->remember_me === null) {
        //     setcookie('login_media_username', $username, 100);
        //     setcookie('login_media_pass', $password, 100);
        // } else {
        //     setcookie('login_media_username', $username, 2147483647);
        //     setcookie('login_media_pass', $password, 2147483647);
        // }

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember_me)) {
            $user = User::find(auth()->user()->id);

            //response
            return response()->json(['success' => true, 'success_msg' => 'Login successful'], 200);
        }
    }

    //logout
    public function logout()
    {
        Auth::logout();
        return Redirect::route('login');
    }
}
