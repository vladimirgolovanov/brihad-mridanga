<?php

namespace App\Http\Controllers;

use App\Report;
use App\Operation;
use App\Person;
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
        self::fill_reports();
        $rs = Report::get_all_reports();
        $all_reports = Operation::reports();
        $prev_date = Operation::get_first_operation_date();
        $last_compiled = '';
        foreach($rs as $k => $r) {
            if($r->compiled) $last_compiled = $r->custom_date;
            $rs[$k]->custom_date_start = date("Y-m-d", strtotime($prev_date. ' +1 day'));
            $prev_date = $r->custom_date;
            $rs[$k]->persons = [];
            $rs[$k]->donation = 0;
            $rs[$k]->debt = 0;
            $rs[$k]->total = 0;
            $rs[$k]->points = 0;
            $rs[$k]->gain = 0;
            $rs[$k]->buying_price = 0;
            $rs[$k]->maha = 0;
            $rs[$k]->big = 0;
            $rs[$k]->middle = 0;
            $rs[$k]->small = 0;
            if(!$r->compiled) {
                $reports = Operation::reports($r->custom_date);
            } else {
                $reports = $all_reports;
            }
            foreach($reports['persons'] as $p) {
                if(isset($p->reports[$r->custom_date])) {
                    $rs[$k]->persons[] = $p->reports[$r->custom_date];
                    $rs[$k]->donation += $p->reports[$r->custom_date]->donation;
                    $rs[$k]->debt += $p->reports[$r->custom_date]->debt;
                    $rs[$k]->total += $p->reports[$r->custom_date]->total;
                    $rs[$k]->points += $p->reports[$r->custom_date]->points;
                    $rs[$k]->gain += $p->reports[$r->custom_date]->gain;
                    $rs[$k]->buying_price += $p->reports[$r->custom_date]->buying_price;
                    $rs[$k]->maha += $p->reports[$r->custom_date]->maha;
                    $rs[$k]->big += $p->reports[$r->custom_date]->big;
                    $rs[$k]->middle += $p->reports[$r->custom_date]->middle;
                    $rs[$k]->small += $p->reports[$r->custom_date]->small;
                } elseif(!$r->compiled && !$p->hide) {
                    $pp = new \stdClass;
                    $pp->id = $p->id;
                    $pp->name = $p->name;
                    $pp->no_remains = true;
                    $pp->laxmi = $p->laxmi;
                    $pp->current_books_price = $p->current_books_price;
                    $pp->debt = $p->debt;
                    $pp->pgroup = $p->persongroup_name;
                    $rs[$k]->persons[] = $pp;
                }
            }
        }
        rsort($rs);
        return ['reports' => $rs, 'last_compiled' => $last_compiled];
    }

    public function fill_reports()
    {
        if(!DB::table('reports')->count()) {
            $reports = DB::table('operations AS o')
                ->where('operation_type', 10)
                ->groupBy('o.custom_date')
                ->select('o.custom_date')
                ->get();
            foreach ($reports as $r) {
                $report = new Report;
                $report->custom_date = $r->custom_date;
                $report->save();
            }
        }
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
            $last = DB::table('reports')
                ->where('compiled', 1)
                ->orderBy('custom_date', 'desc')
                ->first();
            if($last) {
                $report = Report::findOrFail($request->id);
                DB::table('reports')
                    ->where('custom_date', $report->compiled?'>=':'<=', $request->id)
                    ->update(['compiled' => $report->compiled?null:1]);
            } else {
                DB::table('reports')
                    ->where('custom_date', '<=', $request->id)
                    ->update(['compiled' => 1]);
            }
            $report = Report::findOrFail($request->id);
            $report->compiled = $report->compiled?null:1;
        } else {
            $report = new Report;
            $input = $request->all();
            $report->fill($input);
            $report->save();
        }
        return self::index();
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
