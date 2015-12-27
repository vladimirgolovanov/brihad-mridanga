@extends('layouts.master')
 
@section('content')

    <style>
        .trunc {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

<h1>Report</h1>

<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp" style="width: 100%; table-layout: fixed;">
    <thead>
    <tr>
        <th class="mdl-data-table__cell--non-numeric" style="width:150px;">Person</th>
        <th>Maha</th>
        <th>Big</th>
        <th>Middle</th>
        <th>Small</th>
        <th>Total books</th>
        <th>Total points</th>
        <th>Gain</th>
        <th>Donation</th>
        <th>Debt</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
@foreach($report as $v)
    <tr id="{{ $v->person_id }}">
        <td class="mdl-data-table__cell--non-numeric trunc">{{ $v->name }}</td>
        <td>{{ $v->maha }}</td>
        <td>{{ $v->big }}</td>
        <td>{{ $v->middle }}</td>
        <td>{{ $v->small }}</td>
        <td>{{ $v->total }}</td>
        <td>{{ $v->points }}</td>
        <td>{{ $v->gain }}</td>
        <td>{{ $v->donation }}</td>
        <td>{{ $v->debt }}</td>
        <td>{{ $v->balance }}</td>
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
        <td></td>
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
        <th>Balance</th>
    </tr>
    </thead>
</table>
<p id="test">
<form method="POST" accept-charset="UTF-8">
    <?php echo csrf_field(); ?>
    <input type="hidden" id="persons" name="persons" value="">
    <input type="submit" id="recalc" value="Recalculate for selected!">
</form>
</p>
<script>
    $(document).ready(function() {
        $('#recalc').on("click", function(event) {
            $('#persons').val('');
            $('.is-selected').each(function() {
                if($('#persons').val()) $('#persons').val($('#persons').val() + ',' + $(this).attr('id'));
                else $('#persons').val($(this).attr('id'));
            });
        });
    });
</script>

@stop
