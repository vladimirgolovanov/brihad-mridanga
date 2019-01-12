<?php

namespace App\Http\Controllers;

use App\Report;
use App\Operation;
use Auth;
use DB;

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
    public function index()
    {
        $rs = Operation::reports();
        $reports = [];
        foreach($rs['persons'] as $p) {
            foreach($p->reports as $k =>$r) {
                if (!isset($reports[$k])) {
                    $reports[$k] = new \stdClass();
                    $reports[$k]->persons = [];
                }
                foreach ($r as $v) {
                    if ($v) {
                        $reports[$k]->persons[$p->id] = $r;
                        break;
                    }
                }
            }
        }
        krsort($reports);
        return ['reports' => $reports];
    }

    public function fill_reports()
    {
        $reports = DB::table('operations AS o')
            ->where('operation_type', 10)
            ->groupBy('o.custom_date')
            ->select('o.custom_date')
            ->get();
        return $reports;
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
        if($request->id) {
            $report = Report::findOrFail($request->id);
        } else {
            $report = new Report;
        }
        $input = $request->all();
        $report->fill($input);
        $report->save();
        return $report;
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
