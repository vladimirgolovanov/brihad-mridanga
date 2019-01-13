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


    <div class="content-grid mdl-grid">
        <div class="mdl-cell mdl-textfield mdl-js-textfield getmdl-select">
            <input type="text" value="" class="mdl-textfield__input" id="sample1" readonly>
            <input type="hidden" value="" name="sample1">
            <label for="sample1" class="mdl-textfield__label">From</label>
            <ul for="sample1" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                @foreach(array_slice($checkpoints, 1) as $cp)
                    <li class="mdl-menu__item"{{ $begin_date==date("Y-m-d", strtotime($cp->custom_date. ' +1 day'))?' data-selected="true"':'' }} data-val="{{ date("Y-m-d", strtotime($cp->custom_date. ' +1 day')) }}">{{ date("j M Y", strtotime($cp->custom_date. ' +1 day')) }}</li>
                @endforeach
                <li class="mdl-menu__item"{{ $begin_date?'':' data-selected="true"' }} data-val="2015-01-01">Begining</li>
            </ul>
        </div>
        <div class="mdl-cell mdl-textfield mdl-js-textfield getmdl-select">
            <input type="text" value="" class="mdl-textfield__input" id="sample2" readonly>
            <input type="hidden" value="" name="sample2">
            <label for="sample2" class="mdl-textfield__label">To</label>
            <ul for="sample2" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                <?php $i = 0; ?>
                @foreach($checkpoints as $cp)
                    <li class="mdl-menu__item"{{ (!$i && !$end_date || $end_date==$cp->custom_date)?' data-selected="true"':'' }} data-val="{{ date("Y-m-d", strtotime($cp->custom_date)) }}">{{ date("j M Y", strtotime($cp->custom_date)) }}</li>
                    <?php $i = 1; ?>
                @endforeach
            </ul>
        </div>
        <div class="mdl-cell">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="calcperiod">
                Calculate for period
            </button>
        </div>
    </div>

<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp" style="width: 1000px; table-layout: fixed;">
    <thead>
    <tr>
        <th class="mdl-data-table__cell--non-numeric" style="width:200px;">Person</th>
        <th>Maha</th>
        <th>Big</th>
        <th>Middle</th>
        <th>Small</th>
        <th>Total books</th>
        <th>Total points</th>
        <th>Buying</th>
        <th>Gain</th>
        <th>COSKR</th>
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
        <td>{{ $v->buying_price }}</td>
        <td>{{ $v->gain - $v->buying_price * 0.06 }}</td>
        <td>{{ $v->buying_price * 0.06 }}</td>
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
        <td>{{ $totals['buying_price'] }}</td>
        <td>{{ $totals['gain'] - $totals['buying_price'] * 0.06 }}</td>
        <td>{{ $totals['buying_price'] * 0.06 }}</td>
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
        <th>Buying</th>
        <th>Gain</th>
        <th>COSKR</th>
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
        $('#calcperiod').on("click", function(event) {
            window.location.href = '/report/'+$('[name=sample1]').val()+'/'+$('[name=sample2]').val();
        });
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
