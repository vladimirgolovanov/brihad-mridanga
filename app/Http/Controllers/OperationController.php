<?php

namespace App\Http\Controllers;

use App\Book;
use App\Group;
use App\Operation;

use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OperationController extends Controller
{
    /**
     * Show the form for making order on books.
     *
     * @return Response
     */
    public function make($personid)
    {
        // тут и далее должен учавствовать person id
        list($books, $price) = Book::get_all_books(Auth::user()->id);
        return view('operation.make', ['books' => $books, 'price' => $price, 'operation_type' => 1, 'personid' => $personid]);
    }
    /**
     * Show the form for adding laxmi.
     *
     * @return Response
     */
    public function laxmi($personid)
    {
        return view('operation.laxmi', ['operation_type' => 2, 'personid' => $personid]);
    }
    /**
     * Show the form for setting remains.
     *
     * @return Response
     */
    public function remain($personid)
    {
        list($books, $price) = Book::get_all_books(Auth::user()->id);
        return view('operation.remain', ['books' => $books, 'operation_type' => 3, 'personid' => $personid]);
    }
    /**
     * Show the form for returning books.
     *
     * @return Response
     */
    public function booksreturn($personid)
    {
        list($books, $price) = Book::get_all_books(Auth::user()->id);
        return view('operation.return', ['books' => $books, 'operation_type' => 4, 'personid' => $personid]);
    }

    public function store(Request $request)
    {
        $timestamp = date("Y-m-d H:i:s");
        /*$this->validate($request, [
            'personid' => 'required'
        ]);*/
        // возможно есть 1, 3, 4 и есть 2; 1, 3, 4 можно объединить
        if($request->custom_date_switch) {
            $timestamp = $request->custom_date.date(" H:i:s");
        } // Есть вероятность, что пройдет 0000 - необходимо найти этот случай
        if(in_array($request->operation_type, [1,3,4])) {
            foreach($request->bookcount as $bookid => $count) {
                if($count) {
                    $operation = new Operation;
                    $operation->book_id = $bookid;
                    $operation->quantity = $count;
                    $operation->person_id = $request->personid;
                    $operation->datetime = $timestamp;
                    $operation->operation_type = $request->operation_type;
                    $operation->save();
                }
            }
        } elseif($request->operation_type == 2) {
            $operation = new Operation;
            $operation->datetime = $timestamp;
            $operation->person_id = $request->personid;
            $operation->laxmi = $request->laxmi;
            $operation->operation_type = $request->operation_type;
            $operation->save();
        }
        return redirect()->route('persons.show', $request->personid);
    }
}
