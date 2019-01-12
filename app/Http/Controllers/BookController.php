<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookGroup;

use Auth;

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
        $books = Book::get_all_books();
        $bookgroups = BookGroup::get_all_bookgroups();
        return ['books' => $books, 'bookgroups' => $bookgroups];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if($request->id) {
            $book = Book::findOrFail($request->id);
        } else {
            $book = new Book;
        }
        $input = $request->all();
        $book->fill($input);
        $book->bookgroup_id = $request->bookgroup_id ?: null;
        $book->save();
        $book->bookgroup_name = $book->bookgroup_id?BookGroup::get_bookgroup_name($book->bookgroup_id):null;
        return $book;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
