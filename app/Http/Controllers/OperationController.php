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
    public function make_shop($personid, $datetime = '', $custom_date = '')
    {
        return self::make($personid, $datetime, $custom_date, true);
    }
    /**
     * Show the form for making order on books.
     *
     * @return Response
     */
    public function make($personid, $datetime = '', $custom_date = '', $shop = false)
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
                $custom_date = $o->custom_date;
            }
            $editing['bookvalues'] = $bookvalues;
            $editing['price'] = $price;
        }
        $books = Book::get_all_books(Auth::user()->id);
        $os = DB::table('operations')
            ->where('operation_type', '=', 1)
            ->orderBy('custom_date', 'desc')
            ->limit(20)
            ->get();
        foreach($books as $k => $v) {
            $books[$k]->sorting = 0;
        }
//        foreach($os as $o) {
//            foreach($books as $k => $v) {
//                if($books[$k]->id == $o->book_id) $books[$k]->sorting += $o->quantity;
//            }
//        }
//        usort($books, function($a, $b) {
//           if($a->sorting < $b->sorting) {
//               return 1;
//           } elseif($a->sorting == $b->sorting) {
//               if($a->id < $b->id) return -1;
//               else return 1;
//           } else return -1;
//        });
        return view('operation.make', [
            'books' => $books,
            'operation_type' => 1,
            'personid' => $personid,
            'editing' => $editing,
            'datetime' => $datetime,
            'custom_date' => $custom_date,
            'shop' => $shop,
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
            $custom_date = $o->custom_date;
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
    public function remain($personid, $datetime = '', $custom_date = '', $empty = '')
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
                $custom_date = $o->custom_date;
            }
            $editing['bookvalues'] = $bookvalues;
            $books = Book::get_all_books(Auth::user()->id);
        } else {
            list($oss, $bks) = Operation::get_operations($personid);
            $bkss = Book::get_all_books(Auth::user()->id);
            $books = [];
            foreach($bkss as $k => $b) {
                if(isset($bks[$k])) {
                    $books[$k] = $b;
                    $b->havegot = $bks[$k][0];
                }
            }
        }
        return view('operation.remain', [
            'books' => $books,
            'operation_type' => 10,
            'personid' => $personid,
            'editing' => $editing,
            'datetime' => $datetime,
            'custom_date' => $custom_date,
            'empty' => $empty,
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
                $custom_date = $o->custom_date;
            }
            $editing['bookvalues'] = $bookvalues;
            $books = Book::get_all_books(Auth::user()->id);
        } else {
            list($oss, $bks) = Operation::get_operations($personid);
            $bkss = Book::get_all_books(Auth::user()->id);
            $books = [];
            foreach($bkss as $k => $b) {
                if(isset($bks[$k])) {
                    $books[$k] = $b;
                    $b->havegot = $bks[$k][0];
                }
            }
        }
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
        session(['custom_date' => $request->custom_date]);
        if(in_array($request->operation_type, [1,10,4])) {
            if($request->empty_switch) {
                $operation = new Operation;
                $operation->book_id = 0;
                $operation->quantity = 0;
                $operation->person_id = $request->personid;
                $operation->datetime = $datetime;
                $operation->custom_date = $request->custom_date;
                $operation->price = 0;
                $operation->operation_type = $request->operation_type;
                $operation->description = $request->description;
                $operation->save();
            } else {
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
