@extends('layouts.master')
 
@section('content')
 
<h1>Report</h1>

<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
    <thead>
    <tr>
        <th class="mdl-data-table__cell--non-numeric">Person</th>
        <th>Maha</th>
        <th>Big</th>
        <th>Middle</th>
        <th>Small</th>
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
        <td>{{ $v->maha }}</td>
        <td>{{ $v->big }}</td>
        <td>{{ $v->middle }}</td>
        <td>{{ $v->small }}</td>
        <td>{{ $v->total }}</td>
        <td>{{ $v->points }}</td>
        <td>{{ $v->gain }}</td>
        <td>{{ $v->donation }}</td>
        <td>{{ $v->debt }}</td>
    </tr>
@endforeach
    </tbody>
    <thead>
    <tr style="font-weight:bold;">
        <td></td>
        <td class="mdl-data-table__cell--non-numeric">Итого</td>
        <td>{{ $totals['maha'] }}</td>
        <td>{{ $totals['big'] }}</td>
        <td>{{ $totals['middle'] }}</td>
        <td>{{ $totals['small'] }}</td>
        <td>{{ $totals['total'] }}</td>
        <td>{{ $totals['points'] }}</td>
        <td>{{ $totals['gain'] }}</td>
        <td>{{ $totals['donation'] }}</td>
        <td>{{ $totals['debt'] }}</td>
    </tr>
    <tr>
        <th></th>
        <th class="mdl-data-table__cell--non-numeric">Person</th>
        <th>Maha</th>
        <th>Big</th>
        <th>Middle</th>
        <th>Small</th>
        <th>Total books</th>
        <th>Total points</th>
        <th>Gain</th>
        <th>Donation</th>
        <th>Debt</th>
    </tr>
    </thead>
</table>

    <script>

    </script>

@stop
