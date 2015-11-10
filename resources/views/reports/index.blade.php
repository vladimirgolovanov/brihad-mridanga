@extends('layouts.master')
 
@section('content')
 
<h1>Report</h1>

<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
    <thead>
    <tr>
        <th class="mdl-data-table__cell--non-numeric">Person</th>
        <th>Total books</th>
        <th>Total points</th>
        <th>Gain</th>
        <th>Donation</th>
        <th>Debt</th>
    </tr>
    </thead>
    <tbody>
@foreach($report as $v)
    <tr>
        <td class="mdl-data-table__cell--non-numeric">{{ $v->name }}</td>
        <td>{{ $v->total }}</td>
        <td>{{ $v->points }}</td>
        <td>{{ $v->gain }}</td>
        <td>{{ $v->donation }}</td>
        <td>{{ $v->debt }}</td>
    </tr>
@endforeach
    <tr style="font-weight:bold;">
        <td class="mdl-data-table__cell--non-numeric">Итого</td>
        <td>{{ $totals['total'] }}</td>
        <td>{{ $totals['points'] }}</td>
        <td>{{ $totals['gain'] }}</td>
        <td>{{ $totals['donation'] }}</td>
        <td>{{ $totals['debt'] }}</td>
    </tr>
<tr>
    <td class="mdl-data-table__cell--non-numeric">Махабиги</td>
    <td>{{ $totals['maha'] }}</td>
</tr>
<tr>
    <td class="mdl-data-table__cell--non-numeric">Биги</td>
    <td>{{ $totals['big'] }}</td>
</tr>
<tr>
    <td class="mdl-data-table__cell--non-numeric">Средние</td>
    <td>{{ $totals['middle'] }}</td>
</tr>
<tr>
    <td class="mdl-data-table__cell--non-numeric">Маленькие</td>
    <td>{{ $totals['small'] }}</td>
</tr>
    </tbody>
</table>

@stop
