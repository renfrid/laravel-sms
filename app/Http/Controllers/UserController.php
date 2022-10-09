<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Users';
        //has_allowed('roles', 'lists');

        $users = User::all();

        //render view
        return view('users.lists', compact('users', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Register New User';

        //roles
        $roles = Role::all();

        //render view
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, User::rules(), User::messages());

        $password = $request->input('password');

        //create user to store data
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($password),
            'active' => 1,
        ]);
        $user->save();

        //insert roles
        foreach ($request->input('role_ids') as $role_id) {
            $user->roles()->attach($role_id);
        }

        //redirect
        return Redirect::route('users.index')->with('success', 'User registered');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Edit User';

        //user
        $user = User::findOrFail($id);

        //roles
        $roles = Role::all();

        //current roles
        $current_roles = $user->roles;

        //render view
        return view('users.edit', compact('user', 'roles', 'current_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'email' => 'required|string|max:255',
                'phone' => ['required', new PhoneNumber()],
                'role_ids' => 'required'
            ],
            [
                'name.required' => 'Full name is required',
                'email.required' => 'Email is required',
                'phone.required' => 'Phone is required',
                'role_ids.required' => 'Role(s) required'
            ]
        );

        //validate password
        if ($request->input('password') != null || $request->input('password') != '') {
            $this->validate($request, [
                'password' => 'required|string|min:8',
                'password_confirm' => 'required|string|min:8|same:password'
            ], [
                'password.required' => 'Password is required',
                'password.min:8' => 'Password must be 8 or more than character',
                'password_confirm.required' => 'Password confirm is required',
                'password_confirm.same:password' => 'Password confirm must match password'
            ]);
        }

        //create object to store data
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($request->input('password') != null || $request->input('password') != '')
            $user->password = Hash::make($request->input('password'));

        $user->save();

        //delete role user
        foreach ($user->roles as $val) {
            $user->roles()->detach($val->id);
        }

        //insert roles
        foreach ($request->input('role_ids') as $role_id) {
            $user->roles()->attach($role_id);
        }

        //redirect
        return Redirect::route('users.index')->with('success', 'User updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $title = 'Delete User';
        // has_allowed('cars', 'delete');

        $user = User::findOrFail($id);

        if ($del = $user->delete()) {
            //delete role user
            foreach ($user->roles as $val) {
                $user->roles()->detach($val->id);
            }

            //redirect
            return Redirect::route('users.index')->with('success', 'User deleted');
        } else {
            return Redirect::route('users.index')->with('danger', 'Failed to delete user');
        }
    }
}
