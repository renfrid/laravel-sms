<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ContactGroupController extends Controller
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
        $title = 'Contact Groups';
        //has_allowed('roles', 'lists');

        $groups = Group::orderBy('name')->paginate(50);

        //render view
        return view('groups.lists', compact('title', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Register New Contact';

        //render view
        return view('groups.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation 
        $this->validate(
            $request,
            [
                'name' => ['required'],
            ],
            [
                'name.required' => 'Name required',
            ]
        );

        //create new group
        $group = new Group([
            'name' => $request->input('name'),
            'created_by' => Auth::user()->id
        ]);
        $group->save();

        //redirect
        return Redirect::route('contact-groups.create')->with('success', 'Contact group created!');
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
        $title = 'Edit Contact';

        $group = Group::findOrFail($id);

        //render view
        return view('groups.edit', compact('title', 'group'));
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
        //validation 
        $this->validate(
            $request,
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Name required',
            ]
        );

        //update group
        $group = Group::findOrFail($id);
        $group->name = $request->input('name');
        $group->updated_by = Auth::user()->id;
        $group->save();

        //redirect
        return Redirect::route('contact-groups.index')->with('success', 'Contact group updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);

        if ($del = $group->delete()) {
            //find all contact in this group
            $group_contacts = ContactGroup::where(['group_id' => $id])->get();

            foreach ($group_contacts as $val) {
                //delete the contact
                Contact::where(['id' => $val->contact_id])->delete();
            }
            //delete contact group
            $group->contacts()->detach();

            //redirect
            return Redirect::route('contact-groups.index')->with('success', 'Contact group deleted!');
        } else {
            return Redirect::route('contact-groups.index')->with('danger', 'Failed to delete contact group!');
        }
    }

    //concacts
    function contacts($id)
    {
        $title = 'Group Contacts';

        //group
        $group = Group::findOrFail($id);

        //assigned groups
        $arr_contacts = [];

        if ($group->contacts) {
            foreach ($group->contacts as $val) {
                array_push($arr_contacts, $val->id);
            }
        }

        //contacts
        $contacts = Contact::all();

        //render view
        return view('groups.contacts', compact('title', 'group', 'contacts', 'arr_contacts'));
    }

    //assign contact to a group
    function assign_contacts(Request $request, $id)
    {
        //validation 
        // $this->validate(
        //     $request,
        //     [
        //         'name' => 'required',
        //         'phone' => ['required']
        //     ],
        //     [
        //         'name.required' => 'Contact name required',
        //         'phone.required' => 'Phone number required',
        //     ]
        // );

        //group
        $group = Group::findOrFail($id);

        //insert contact group
        foreach ($request->input('contact_ids') as $contact_id) {
            $group->contacts()->sync($contact_id);
        }

        //redirect
        return Redirect::route('contact-groups.contacts', $id)->with('success', 'Contact(s) assigned/removed to group!');
    }

    //assign contact groups
    function assign_contacts_groups()
    {
        $title = 'Group Contacts';

        //group
        $groups = Group::all();

        //contacts
        $contacts = Contact::all();

        //render view
        return view('groups.assign_contacts_groups', compact('title', 'groups', 'contacts'));
    }

    //assign contact to a group
    function store_contacts_groups(Request $request)
    {
        //validation 
        $this->validate(
            $request,
            [
                'group_id' => 'required',
                'contact_ids' => ['required']
            ],
            [
                'group_id.required' => 'Contact group required',
                'contact_ids.required' => 'Contact required',
            ]
        );

        //post data
        $group_id = $request->input('group_id');

        //insert contact group
        foreach ($request->input('contact_ids') as $contact_id) {
            $contact_group = ContactGroup::firstOrNew([
                'contact_id' => $contact_id,
                'group_id' => $group_id
            ]);
            $contact_group->save();
        }

        //redirect
        return Redirect::route('contact-groups.index')->with('success', 'Contact(s) assigned to group!');
    }
}
