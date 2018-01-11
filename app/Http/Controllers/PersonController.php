<?php

namespace App\Http\Controllers;

use App\Person;
use App\Operation;
use App\Book;

use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{
    public function index($param = null)
    {
        if($param == 'all') {
            $persons = Person::where('user_id', Auth::user()->id)->orderBy('hide')->orderBy('name')->get();
        } else {
            $persons = Person::where('user_id', Auth::user()->id)->whereNull('hide')->orderBy('hide')->orderBy('name')->get();
        }
        $ps = [];
        foreach($persons as $k => $p) {
            $p->last_remains_date = Operation::get_last_remains_date($p->id);
            list($os, $books, $lxm, $laxmi, $current_books_price, $debt, $osgrp) = Operation::get_operations($p->id);
            $p->debt = $debt;
            $p->laxmi = $laxmi;
            $p->current_books_price = $current_books_price;
            $ps[] = $p;
        }
        usort($ps, function($a, $b) {
            return $b->debt > $a->debt;
        });
        return $ps;
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
        if($request->id) {
            $person = Person::findOrFail($request->id);
        } else {
            $person = new Person;
        }
        $person->name = $request->name;
        $person->hide = $request->hide;
        $person->save();
        if($request->id) {
            $person->last_remains_date = Operation::get_last_remains_date($request->id);
            list($os, $books, $lxm, $laxmi, $current_books_price, $debt, $osgrp) = Operation::get_operations($request->id);
            $person->debt = $debt;
            $person->laxmi = $laxmi;
            $person->current_books_price = $current_books_price;
        }
        return $person;
    }

    public function current_books($id, $tilldate) {
        list($os, $current_books) = Operation::get_operations($id, $tilldate);
        $books = Book::get_all_books(Auth::user()->id);
        $boooks = [];
        foreach($current_books as $k => $v) {
            $boooks[$k] = $books[$k];
            $boooks[$k]->current_qty = $v[0];
            $boooks[$k]->qty = null;
        }
        return $boooks;
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
        list($os, $books, $lxm, $laxmi, $current_books_price, $debt, $osgrp) = Operation::get_operations($id);
        $person['books'] = $books;
        $person['lxm'] = $lxm;
        $person['laxmi'] = $laxmi;
        $person['current_books_price'] = $current_books_price;
        $person['debt'] = $debt;
        $person['osgrp'] = $osgrp;
        return $person;
        /*$person = Person::findOrFail($id);
        list($operations, $summ, $books) = Operation::get_all_operations($id);
        $operation_type_name = Operation::operation_type_name($id);
        $book_names_by_id = Operation::book_names_by_id();
        return view('persons.show', [
            'operations' => $operations,
            'person' => $person,
            'summ' => $summ,
            'books' => $books,
            'operation_type_name' => $operation_type_name,
            'book_names_by_id' => $book_names_by_id,
            ]);*/
    }

    public function make($data) {

    }

    public function operation($personid, $operationid)
    {
        $person = Person::findOrFail($personid);
        list($operations, $summ, $books) = Operation::get_all_operations($personid, $operationid);
        $operation_type_name = Operation::operation_type_name($personid);
        $book_names_by_id = Operation::book_names_by_id();
        return view('persons.operation', [
            'operations' => $operations,
            'person' => $person,
            'summ' => $summ,
            'books' => $books,
            'operation_type_name' => $operation_type_name,
            'book_names_by_id' => $book_names_by_id,
            ]);
    }

    public function edit_operation($personid, $operationid, $bookid) {
        $operation = Operation::get_current_operation($personid, $operationid, $bookid);
        return view('persons.edit_operation')->withOperation($operation);
    }

    public function store_operation(Request $request, $personid, $operationid, $bookid)
    {
        /*$this->validate($request, [
            'datetime' => 'required',
        ]);*/ // здесь должна быть валидация
        $person = Operation::where('person_id', $personid)->where('datetime', $operationid)->where('book_id', $bookid)
            ->update([
                'datetime' => $request->datetime,
                'quantity' => $request->quantity,
                'laxmi' => $request->laxmi,
            ]);
        return redirect()->route('persons.operation', ['personid' => $personid, 'operationid' => $operationid]);
    }
    public function destroy_operation($personid, $operationid, $bookid)
    {
        $person = Operation::where('person_id', $personid)->where('datetime', $operationid)->where('book_id', $bookid)
        ->delete();
        return redirect()->route('persons.show', $personid);
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
            'name' => 'required',
        ]);
        $input = $request->all();
        $person->fill($input);
        $person->hide = $request->hide?1:null;
        $person->save();
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
