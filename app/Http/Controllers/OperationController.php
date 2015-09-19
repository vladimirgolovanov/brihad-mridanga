<?php

namespace App\Http\Controllers;

use App\Book;
use App\Group;
use App\Operation;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OperationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function make()
    {
        list($books, $price) = Book::get_all_books();
        return view('operation.make', ['books' => $books, 'price' => $price, 'operation_type' => 1]);
    }

    public function store(Request $request)
    {
        $timestamp = date("Y-m-d H:i:s");
        /*$this->validate($request, [
            'personid' => 'required'
        ]);*/
        $personid = 1;
        foreach($request->bookcount as $bookid => $count) {
            $operation = new Operation;
            $operation->book_id = $bookid;
            $operation->quantity = $count;
            $operation->person_id = $personid;
            $operation->datetime = $timestamp;
            $operation->operation_type = $request->operation_type;
            $operation->save();
            //save data here: [increment_id, timestamp?], qty (count), bookid, data, personid (book_price_id?)
        }
        return redirect()->route('books.index');
    }
}
