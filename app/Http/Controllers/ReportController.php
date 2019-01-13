<?php

namespace App\Http\Controllers;

use App\Person;
use App\Operation;
use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($begin_date = 0, $end_date = 0)
    {
        list($report, $totals) = Operation::monthly_report($begin_date, $end_date, []);
        $checkpoints = Operation::checkpoints();
        return view('reports.index', ['totals' => $totals, 'checkpoints' => $checkpoints, 'begin_date' => $begin_date, 'end_date' => $end_date])->withReport($report);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getselected(Request $request, $begin_date = 0, $end_date = 0)
    {
        $persons = explode(',', $request->persons);
        list($report, $totals) = Operation::monthly_report($begin_date, $end_date, $persons);
        $checkpoints = Operation::checkpoints();
        return view('reports.index', ['totals' => $totals, 'checkpoints' => $checkpoints, 'begin_date' => $begin_date, 'end_date' => $end_date])->withReport($report);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
