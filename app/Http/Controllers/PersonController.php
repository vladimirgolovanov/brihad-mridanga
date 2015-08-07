<?php

namespace App\Http\Controllers;

use App\Person;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $persons = Person::all();
        return view('persons.index')->withPersons($persons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('persons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $input = $request->all();
        Person::create($input);
        //Session::flash('flash_message', 'Person successfully added!');
        return redirect()->route('persons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $person = Person::findOrFail($id);
        return view('persons.show')->withPerson($person);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $person = Person::findOrFail($id);
        return view('persons.edit')->withPerson($person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        $this->validate($request, [
            'name' => 'required'
        ]);
        $input = $request->all();
        $person->fill($input)->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();
        return redirect()->route('persons.index');
    }
}
