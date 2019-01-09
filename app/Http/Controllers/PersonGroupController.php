<?php

namespace App\Http\Controllers;

use App\PersonGroup;
use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PersonGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $persongroups = PersonGroup::get_all_persongroups(Auth::user()->id);
        return view('persongroups.index', ['persongroups' => $persongroups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('persongroups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->id) {
            $pg = PersonGroup::findOrFail($request->id);
        } else {
            $pg = new PersonGroup;
        }
        $input = $request->all();
        $pg->fill($input);
        $pg->user_id = Auth::user()->id;
        $pg->save();
        return $pg;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pg = PersonGroup::findOrFail($id);
        return view('persongroups.show')->withPersongroup($pg);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pg = PersonGroup::findOrFail($id);
        return view('persongroups.edit')->withPersongroup($pg);
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
        $pg = PersonGroup::findOrFail($id);
        $this->validate($request, [
            'name' => 'required'
        ]);
        $input = $request->all();
        $pg->fill($input)->save();
        return redirect()->route('persongroups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pg = PersonGroup::findOrFail($id);
        $pg->delete();
        return redirect()->route('persongroups.index');
    }
}
