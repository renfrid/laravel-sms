<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $title = 'Roles';
        //has_allowed('roles', 'lists');

        //roles
        $roles = Role::all();

        return view('roles.lists', compact('roles', 'title'));
    }

    //register user
    function create()
    {
        $title = 'Create New';
        //has_allowed('roles', 'create');

        return view('roles.create', compact('title'));
    }

    //store users details
    function store(Request $request)
    {
        //validation
        $this->validate(
            $request,
            [
                'name' => 'required|unique:roles',
                'description' => 'required|string'
            ],
            [
                'name.required' => 'Name required',
                'description.required' => 'Description required'
            ]
        );

        //create role to store data
        $role = new Role();
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->save();

        //redirect
        return Redirect::route('roles.index')->with('success', 'Role created');
    }

    //edit
    function edit($id)
    {
        $title = 'Edit Role';
        //has_allowed('roles', 'edit');

        //role
        $role = Role::findOrFail($id);

        return view('roles.edit', compact('role', 'title'));
    }

    //update user
    public function update(Request $request, $id)
    {
        //validation
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'description' => 'required|string'
            ],
            [
                'name.required' => 'Name required',
                'description.required' => 'Description required'
            ]
        );

        //update role to store data
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->save(); //save

        //redirect
        return Redirect::route('roles.index')->with('success', 'Role updated');
    }

    //assign perms
    // public function assign_perms($id)
    // {
    //     //has_allowed('roles', 'assign_perms');

    //     //title
    //     $title = 'Assign Permission';

    //     //role
    //     $role = Role::findOrFail($id);

    //     //all perms
    //     $perms = Perm::get_perms_all();

    //     //assigned perms
    //     $assigned_perms = [];
    //     $perm_role = PermRole::where('role_id', $id)->first();
    //     ($perm_role) ? $methods = explode(',', $perm_role->methods) : $methods = [];

    //     foreach ($methods as $v) {
    //         array_push($assigned_perms, $v);
    //     }

    //     //render view
    //     return view('roles.assign_perms', compact('title', 'role', 'perms', 'assigned_perms'));
    // }

    //store perms
    // function store_perms(Request $request, $id)
    // {
    //     $perms = $request->perms;
    //     $role_id = $id;

    //     if ($perms) {
    //         $classes = [];
    //         foreach ($perms as $perm) {
    //             //method
    //             $method = PermMethod::where(['id' => $perm])->first();

    //             if (!in_array($method->class_id, $classes))
    //                 array_push($classes, $method->class_id);
    //         }

    //         //insert or update roles
    //         $perm_role = PermRole::firstOrNew(['role_id' => $role_id]);
    //         $perm_role->classes = join(',', $classes);
    //         $perm_role->methods = join(',', $perms);
    //         $perm_role->save();

    //         return Redirect::route('roles.assign-perms', $role_id)->with('success', 'Permission updated');
    //     } else {
    //         return Redirect::route('roles.assign-perms', $role_id)->with('failed', 'Failed to update perms');
    //     }
    // }
}
