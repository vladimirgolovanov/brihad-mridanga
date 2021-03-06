<?php

namespace App\Http\Controllers;

use App\Book;
use App\Group;
use App\Operation;
use App\Person;

use DB; // тут этого не должно быть

use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OperationController extends Controller
{
    public function show($datetime)
    {
        $test = DB::table('operations')
            ->where('datetime', $datetime)
            ->first();
        if($test && ($test->operation_type == 1 || $test->operation_type == 3)) {
            $os = DB::table('operations')
                ->where('datetime', $datetime)
                ->leftJoin('books', 'operations.book_id', '=', 'books.id')
                ->leftJoin('bookgroups', 'books.bookgroup_id', '=', 'bookgroups.id')
                ->select('books.id', 'books.name', 'books.shortname', 'bookgroups.name as bookgroup_name', 'operations.quantity as qty', 'operations.price', 'operations.price_buy', 'book_type')
                ->get();
            return ['books' => $os, 'descr' => $test->description, 'date' => $test->custom_date, 'operation_type' => $test->operation_type];
        } else if($test && ($test->operation_type == 4 || $test->operation_type == 10)) {
            $os = DB::table('operations')
                ->where('datetime', $datetime)
                ->select('book_id', 'operations.quantity as qty')
                ->get();
            $bks = [];
            foreach($os as $b) {
                $bks[$b->book_id] = $b;
            }
            list($os, $current_books) = Operation::get_operations($test->person_id, $test->custom_date, $datetime);
            $books = Book::get_all_books();
            $boooks = [];
            foreach($current_books as $k => $v) {
                $boooks[$k] = $books[$k];
                $boooks[$k]->current_qty = $v[0];
                $boooks[$k]->qty = isset($bks[$k])?$bks[$k]->qty:null;
            }
            return ['books' => $boooks, 'descr' => $test->description, 'date' => $test->custom_date];
        } else if($test && $test->operation_type == 2) {
            return ['payed' => $test->laxmi, 'descr' => $test->description, 'date' => $test->custom_date];
        } else {
            return response('Not found', 404);
        }
    }

    public function store(Request $request) {
        if($request->datetime) {
            $datetime = $request->datetime;
            DB::table('operations')
                ->where('datetime', $datetime)
                ->delete();
        } else {
            $datetime = date("Y-m-d H:i:s");
        }
        if($request->id) {
            $person = Person::findOrFail($request->id);
            $person->hide = null;
            $person->save();
        }
        if($request->exchange_id) {
            $books = Book::get_all_books();
            $datetime2 = date("Y-m-d H:i:s", time()+1);
        }
        if($request->operation_type == 1 || $request->operation_type == 3) {
            foreach ($request->books as $book) {
                if ($book['qty']) {
                    $operation = new Operation;
                    $operation->book_id = $book['id'];
                    $operation->quantity = $book['qty'];
                    $operation->person_id = $request->id;
                    $operation->datetime = $datetime;
                    $operation->custom_date = date("Y-m-d H:i:s", strtotime($request->date));
                    $operation->price = $book['price'];
                    $operation->price_buy = $book['price_buy'];
                    $operation->operation_type = $request->operation_type;
                    $operation->description = $request->descr;
                    $operation->save();
                }
            }
        }
        if($request->operation_type == 4 || $request->operation_type == 10) {
            $totalqty = 0;
            foreach ($request->books as $book) {
                $totalqty += $book['qty'];
                if ($book['qty']) {
                    $operation = new Operation;
                    $operation->book_id = $book['id'];
                    $operation->quantity = $book['qty'];
                    $operation->person_id = $request->id;
                    $operation->datetime = $datetime;
                    $operation->custom_date = date("Y-m-d H:i:s", strtotime($request->date));
                    $operation->price = 0;
                    $operation->price_buy = 0;
                    $operation->operation_type = $request->operation_type;
                    $operation->description = $request->descr;
                    $operation->save();
                    if($request->exchange_id) {
                        $operation = new Operation;
                        $operation->book_id = $book['id'];
                        $operation->quantity = $book['qty'];
                        $operation->person_id = $request->exchange_id;
                        $operation->datetime = $datetime2;
                        $operation->custom_date = date("Y-m-d H:i:s", strtotime($request->date));
                        $operation->price = $books[$book['id']]->price;
                        $operation->price_buy = $books[$book['id']]->price_buy;
                        $operation->operation_type = 1;
                        $operation->description = $request->descr;
                        $operation->save();
                    }
                }
            }
            if($request->operation_type == 10 && $request->empty) {
                $operation = new Operation;
                $operation->book_id = 0;
                $operation->quantity = 0;
                $operation->person_id = $request->id;
                $operation->datetime = $datetime;
                $operation->custom_date = date("Y-m-d H:i:s", strtotime($request->date));
                $operation->price = 0;
                $operation->price_buy = 0;
                $operation->operation_type = $request->operation_type;
                $operation->description = $request->descr;
                $operation->save();
            }
        }
        if($request->payed) {
            $datetime = date("Y-m-d H:i:s", time()+1);
            $operation = new Operation;
            $operation->datetime = $datetime;
            $operation->custom_date = date("Y-m-d H:i:s", strtotime($request->date));
            $operation->person_id = $request->id;
            $operation->laxmi = $request->payed;
            $operation->operation_type = 2;
            $operation->description = $request->descr;
            $operation->save();
        }
    }
}
