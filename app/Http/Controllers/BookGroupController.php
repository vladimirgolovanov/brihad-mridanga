<?php

namespace App\Http\Controllers;

use App\BookGroup;
use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookgroups = BookGroup::get_all_bookgroups();
        return view('bookgroups.index', ['bookgroups' => $bookgroups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bookgroups.create');
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
            $bg = BookGroup::findOrFail($request->id);
        } else {
            $bg = new BookGroup;
        }
        $input = $request->all();
        $bg->fill($input);
        $bg->save();
        return $bg;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bg = BookGroup::findOrFail($id);
        return view('bookgroups.show')->withBookgroup($bg);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bg = BookGroup::findOrFail($id);
        return view('bookgroups.edit')->withBookgroup($bg);
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
        $bg = BookGroup::findOrFail($id);
        $this->validate($request, [
            'name' => 'required'
        ]);
        $input = $request->all();
        $bg->fill($input)->save();
        return redirect()->route('bookgroups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bg = BookGroup::findOrFail($id);
        $bg->delete();
        return redirect()->route('bookgroups.index');
    }
}
