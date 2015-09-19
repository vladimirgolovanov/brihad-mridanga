<?php

namespace App\Http\Controllers;

use App\Book;
use App\Group;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /*$groups = Group::all();
        foreach($groups as $group) {
            $books[$group->id] = Book::where('group_id', $group->id)->get();
        }*/
        list($books, $price) = Book::get_all_books();
        return view('books.index', ['books' => $books, 'price' => $price]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $groups = Group::all();
        return view('books.create')->withGroups($groups);
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
            'group_id' => 'required',
        ]);
        $input = $request->all();
        Book::create($input);
        //Session::flash('flash_message', 'Book successfully added!');
        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show')->withBook($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $groups = Group::all();
        return view('books.edit')->withBook($book)->withGroups($groups);
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
        $book = Book::findOrFail($id);
        $this->validate($request, [
            'name' => 'required'
        ]);
        $input = $request->all();
        $book->fill($input)->save();
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
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books.index');
    }
}
