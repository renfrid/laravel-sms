<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Templates';
        //has_allowed('roles', 'lists');

        //templates
        $templates = Template::all();

        return view('templates.lists', compact('templates', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create New';
        //has_allowed('roles', 'create');

        return view('templates.create', compact('title'));
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
                'message' => 'required|string'
            ],
            [
                'name.required' => 'Title required',
                'message.required' => 'Message required'
            ]
        );

        //create role to store data
        $template = new Template([
            'name' => $request->input('name'),
            'message' => $request->input('message'),
            'created_by' => Auth::user()->id
        ]);
        $template->save();

        //redirect
        return Redirect::route('templates.index')->with('success', 'Message template  created');
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
        $title = 'Edit Template';
        //has_allowed('roles', 'edit');

        //template
        $template = Template::findOrFail($id);

        return view('templates.edit', compact('template', 'title'));
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
                'message' => 'required|string'
            ],
            [
                'name.required' => 'Title required',
                'message.required' => 'Message required'
            ]
        );

        //update data
        $template = Template::findOrFail($id);
        $template->name = $request->input('name');
        $template->message = $request->input('message');
        $template->updated_by = Auth::user()->id;
        $template->save(); //save

        //redirect
        return Redirect::route('templates.index')->with('success', 'Message template updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = Template::findOrFail($id);

        if ($del = $template->delete()) {

            //redirect
            return Redirect::route('templates.index')->with('success', 'Template message deleted!');
        } else {
            return Redirect::route('templates.index')->with('danger', 'Failed to delete template message!');
        }
    }

    /**
     * get the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function get_data($id)
    {
        $template = Template::findOrFail($id);

        if ($template) {
            return response()->json(['error' => false, 'message' => $template->message], 200);
        } else {
            return response()->json(['error' => true, 'error_msg' => "Template not found"], 500);
        }
    }
}
