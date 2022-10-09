<?php

namespace App\Http\Controllers;

use App\Imports\ContactImport;
use App\Models\Contact;
use App\Models\Group;
use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
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
        $title = 'Contacts';
        //has_allowed('roles', 'lists');

        $contacts = Contact::orderBy('name')->paginate(100);

        //render view
        return view('contacts.lists', compact('title', 'contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Register New Contact';

        //groups
        $groups = Group::all();

        //render view
        return view('contacts.create', compact('title', 'groups'));
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
                'name' => 'required',
                'phone' => ['required', new PhoneNumber(), 'unique:contacts']
            ],
            [
                'name.required' => 'Contact name required',
                'phone.required' => 'Phone number required',
            ]
        );

        //create new contacts
        $contact = new Contact([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'created_by' => Auth::user()->id
        ]);
        $contact->save();

        //insert contact group
        foreach ($request->input('group_ids') as $group_id) {
            $contact->groups()->attach($group_id);
        }

        //redirect
        return Redirect::route('contacts.create')->with('success', 'Contact registered!');
    }

    //import
    public function import()
    {
        $title = 'Import Contacts';

        //groups
        $groups = Group::all();

        //render view
        return view('contacts.import', compact('title', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_import(Request $request)
    {
        //validation 
        $this->validate(
            $request,
            [
                'attachment' => 'required',
            ],
            [
                'attachment.required' => 'Contact name required',
            ]
        );

        //path
        $path = $request->file('attachment');
        $groups = $request->input('group_ids');

        //import new contacts
        Excel::import(new ContactImport($groups), $path);

        //redirect
        return Redirect::route('contacts.import')->with('success', 'Contacts imported!');
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

        $contact = Contact::findOrFail($id);

        //groups
        $groups = Group::all();

        //assigned groups
        $arr_groups = [];

        if ($contact->groups) {
            foreach ($contact->groups as $val) {
                array_push($arr_groups, $val->id);
            }
        }

        //render view
        return view('contacts.edit', compact('title', 'contact', 'groups', 'arr_groups'));
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
                'phone' => ['required', new PhoneNumber()]
            ],
            [
                'name.required' => 'Contact name required',
                'phone.required' => 'Phone number required',
            ]
        );

        //update contact
        $contact = Contact::findOrFail($id);
        $contact->name = $request->input('name');
        $contact->phone = $request->input('phone');
        $contact->updated_by = Auth::user()->id;
        $contact->save();

        //delete contact group
        foreach ($contact->groups as $val) {
            $contact->groups()->detach($val->id);
        }

        //insert contact group
        foreach ($request->input('group_ids') as $group_id) {
            $contact->groups()->attach($group_id);
        }

        //redirect
        return Redirect::route('contacts.index')->with('success', 'Contact updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        if ($del = $contact->delete()) {
            //delete contact group

            //redirect
            return Redirect::route('contacts.index')->with('success', 'Contact deleted!');
        } else {
            return Redirect::route('contacts.index')->with('danger', 'Failed to delete contact!');
        }
    }
}
