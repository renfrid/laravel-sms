<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //change password
    function change_password()
    {
        $title = 'Change Password';

        //user
        $user = User::findOrFail(Auth::user()->id);

        //render view
        return view('profile.change_password', compact('title', 'user'));
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store_password(Request $request)
    {
        $this->validate(
            $request,
            [
                'current_password' => ['required', new MatchOldPassword()],
                'new_password' => ['required'],
                'new_password_confirm' => ['required', 'same:new_password'],
            ],
            [
                'current_password.required' => 'Current password required',
                'new_password.required' => 'New password required',
                'new_password_confirm.required' => 'Password confirmation required',
                'new_password_confirm.same:new_password' => 'New password password should match',
            ]
        );

        //update user
        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return Redirect::route('profile.change-password')->with('success', 'Password changed');
    }
}
