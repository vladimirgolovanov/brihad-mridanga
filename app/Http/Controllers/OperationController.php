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
    /**
     * Show the form for making order on books.
     *
     * @return Response
     */
    public function make($personid, $datetime = '', $custom_date = '')
    {
        // ВЫНЕСТИ ИЗ КОНТРОЛЛЕРА
        $editing = []; // Если массив не пустой - редактируем.
                       // В любом случае при сохранении можно удялять все.
                       // Если создаем - ничего не теряем. Если редактируем - так и надо.
        if($datetime) {
            // выбираем редактируемые операции
            $os = DB::table('operations')
                ->where('person_id', $personid)
                ->where('datetime', $datetime)
                ->get();
            foreach($os as $o) {
                $bookvalues[$o->book_id] = $o->quantity;
                $price[$o->book_id] = $o->price;
                if($o->description) $editing['description'] = $o->description;
            }
            $editing['bookvalues'] = $bookvalues;
            $editing['price'] = $price;
        }
        list($books, $price) = Book::get_all_books(Auth::user()->id);
        return view('operation.make', [
            'books' => $books,
            'price' => $price,
            'operation_type' => 1,
            'personid' => $personid,
            'editing' => $editing,
            'datetime' => $datetime,
            'custom_date' => $custom_date,
        ]);
    }
    /**
     * Show the form for adding laxmi.
     *
     * @return Response
     */
    public function laxmi($personid, $datetime = '', $custom_date = '')
    {
        // ВЫНЕСТИ ИЗ КОНТРОЛЛЕРА
        $editing = []; // Если массив не пустой - редактируем.
                       // В любом случае при сохранении можно удялять все.
                       // Если создаем - ничего не треяем. Если редактируем - так и надо.
        if($datetime) {
            // выбираем редактируемые операции
            $o = DB::table('operations')
                ->where('person_id', $personid)
                ->where('datetime', $datetime)
                ->first();
            if($o->laxmi) $editing['laxmi'] = $o->laxmi;
            if($o->description) $editing['description'] = $o->description;
        }
        return view('operation.laxmi', [
            'operation_type' => 2,
            'personid' => $personid,
            'editing' => $editing,
            'datetime' => $datetime,
            'custom_date' => $custom_date,
        ]);
    }
    /**
     * Show the form for setting remains.
     *
     * @return Response
     */
    public function remain($personid, $datetime = '', $custom_date = '')
    {
        // ВЫНЕСТИ ИЗ КОНТРОЛЛЕРА
        $editing = []; // Если массив не пустой - редактируем.
                       // В любом случае при сохранении можно удялять все.
                       // Если создаем - ничего не треяем. Если редактируем - так и надо.
        if($datetime) {
            // выбираем редактируемые операции
            $os = DB::table('operations')
                ->where('person_id', $personid)
                ->where('datetime', $datetime)
                ->get();
            foreach($os as $o) {
                $bookvalues[$o->book_id] = $o->quantity;
                if($o->description) $editing['description'] = $o->description;
            }
            $editing['bookvalues'] = $bookvalues;
        }
        list($books, $price) = Book::get_all_books(Auth::user()->id);
        return view('operation.remain', [
            'books' => $books,
            'operation_type' => 10,
            'personid' => $personid,
            'editing' => $editing,
            'datetime' => $datetime,
            'custom_date' => $custom_date,
        ]);
    }
    /**
     * Show the form for returning books.
     *
     * @return Response
     */
    public function booksreturn($personid, $datetime = '', $custom_date = '')
    {
        // ВЫНЕСТИ ИЗ КОНТРОЛЛЕРА
        $editing = []; // Если массив не пустой - редактируем.
                       // В любом случае при сохранении можно удялять все.
                       // Если создаем - ничего не треяем. Если редактируем - так и надо.
        if($datetime) {
            // выбираем редактируемые операции
            $os = DB::table('operations')
                ->where('person_id', $personid)
                ->where('datetime', $datetime)
                ->get();
            foreach($os as $o) {
                $bookvalues[$o->book_id] = $o->quantity;
                if($o->description) $editing['description'] = $o->description;
            }
            $editing['bookvalues'] = $bookvalues;
        }
        list($books, $price) = Book::get_all_books(Auth::user()->id);
        return view('operation.return', [
            'books' => $books,
            'operation_type' => 4,
            'personid' => $personid,
            'editing' => $editing,
            'datetime' => $datetime,
            'custom_date' => $custom_date,
        ]);
    }

    public function store(Request $request)
    {
        $datetime = $request->datetime?$request->datetime:date("Y-m-d H:i:s");
        // возможно есть 1, 10, 4 и есть 2; 1, 10, 4 можно объединить
        // Удаляем все с данным таймстемпом и personid
        $os = DB::table('operations')
            ->where('person_id', $request->personid)
            ->where('datetime', $datetime)
            ->delete();
        if(in_array($request->operation_type, [1,10,4])) {
            foreach($request->bookcount as $bookid => $count) {
                if($count) {
                    $operation = new Operation;
                    $operation->book_id = $bookid;
                    $operation->quantity = $count;
                    $operation->person_id = $request->personid;
                    $operation->datetime = $datetime;
                    $operation->custom_date = $request->custom_date;
                    $operation->price = $request->price[$bookid];
                    $operation->operation_type = $request->operation_type;
                    $operation->description = $request->description;
                    $operation->save();
                }
            }
        } elseif($request->operation_type == 2) {
            $operation = new Operation;
            $operation->datetime = $datetime;
            $operation->custom_date = $request->custom_date;
            $operation->person_id = $request->personid;
            $operation->laxmi = $request->laxmi;
            $operation->operation_type = $request->operation_type;
            $operation->description = $request->description;
            $operation->save();
        }
        return redirect()->route('persons.show', $request->personid);
    }
}
