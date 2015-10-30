<?php

namespace App\Http\Controllers;

use App\Person;
use App\Operation;

use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $persons = Person::where('user_id', Auth::user()->id)->get();
        return view('persons.index')->withPersons($persons);
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
        $this->validate($request, [
            'name' => 'required',
        ]);
        $person = new Person;
        $person->name = $request->name;
        $person->user_id = Auth::user()->id;
        $person->save();
        //Session::flash('flash_message', 'Person successfully added!');
        return redirect()->route('persons.index');
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
        $os = Operation::get_operations($id);
        list($milestones, $dates) = array(array(), array());
        list($milestones, $operations, $summ, $books) = array(array(), array(), array(), array());
        list($milestones, $dates) = Operation::get_operation_milestones($id);
        list($milestones, $operations, $summ, $books) = Operation::get_operations_by_milestone($id, $milestones, $dates);
        $operation_type_name = Operation::operation_type_name();
        $book_names_by_id = Operation::book_names_by_id();
        $milestonedata = Operation::get_milestone_data($id, $milestones);
        return view('persons.show', [
            'milestonedata' => $milestonedata,
            'milestones' => $milestones,
            'operations' => $operations,
            'person' => $person,
            'summ' => $summ,
            'books' => $books,
            'operation_type_name' => $operation_type_name,
            'book_names_by_id' => $book_names_by_id,
            'os' => $os,
            ]);
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
        $person->fill($input)->save();
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
