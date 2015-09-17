<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookPrice;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $books = Book::all();
        foreach($books as $book) {
            print 'asdf';
            $bookprices = Book::where('id', $book->id)->orderBy('created_at', 'desc')->value('name');
            print $bookprices;
            //$bookprices[$book->id] = DB::table('bookprice')->where('id', $book->id)->orderBy('created_at', 'desc')->value('price');
            //$bookprice = BookPrice::where('id', $book->id)->orderBy('created_at', 'desc')->value('price');
            //print $book->price.'<br>';
            print 'asdf';
        }
        //return view('books.index')->withBooks($books)->withGroups($groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
        $book = Book::findOrFail($id);
        $bookprice = BookPrice::where('book_id', $book->id)->orderBy('created_at', 'desc')->value('price');
        //print $bookprice->created_at;
        return view('bookprice.create', [
            'bookname'=>$book->name,
            'bookid'=>$book->id,
            'bookprice'=>$bookprice,
            ]);
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
            'bookprice' => 'required'
        ]);
        $bookprice = new BookPrice();
        $bookprice->price = $request->bookprice;
        $bookprice->book_id = $request->bookid;
        $bookprice->save();
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
