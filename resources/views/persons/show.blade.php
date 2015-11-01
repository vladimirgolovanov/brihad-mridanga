@extends('layouts.master')
 
@section('content')
 
<h1>{{ $person->name }}</h1>

<div>
<a href="{{ route('persons.edit', $person->id) }}" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Редактировать</a>
</div>

<div>
<a href="{{ route('operation.make', $person->id) }}" class="mdl-button mdl-js-button mdl-button--accent">Выдача книг</a>
<a href="{{ route('operation.laxmi', $person->id) }}" class="mdl-button mdl-js-button mdl-button--accent">Сдача лакшми</a>
<a href="{{ route('operation.remain', $person->id) }}" class="mdl-button mdl-js-button mdl-button--accent">Отчет об остатке</a>
<a href="{{ route('operation.return', $person->id) }}" class="mdl-button mdl-js-button mdl-button--accent">Возврат книг</a>
</div>

<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
@foreach($os as $o)
    @if($o['type'] == 'operation')
	<tr<?php if($o['o']->operation_type == 3) { ?> style="background:#ccc;"<?php } ?>>
		<td class="mdl-data-table__cell--non-numeric">
            <a href="{{ route('persons.operation', [$person->id, $o['o']->datetime]) }}"><?php switch($o['o']->operation_type) {
                case 1: print "Выдача книг"; break;
                case 2: print "Сдача лакшми"; break;
                case 3: print "Отчет об остатке книг"; break;
                case 4: print "Возврат книг"; break;
            }
            ?></a></td>
        <td>{{ date("j M", strtotime($o['o']->updated_at)) }}</td>
	</tr>
	@elseif($o['type'] == 'info')
    <tr style="background:#ccc;">
        <td class="mdl-data-table__cell--non-numeric">{{ $o['text'] }}</td>
        <td>{{ $o['o'] }}</td>
    </tr>
    @elseif($o['type'] == 'warning')
        <tr>
            <td class="mdl-data-table__cell--non-numeric">{{ $o['o'] }}:</td>
            <td><span class="material-icons" style="color:red;">error</span></td>
        </tr>
    @else
        @if($o['o']->operation_type == 2)
    <tr>
        <td class="mdl-data-table__cell--non-numeric"></td>
        <td>{{ $o['o']->laxmi }}</td>
    </tr>
        @else
    <tr<?php if($o['o']->operation_type == 3) { ?> style="background:#ccc;"<?php } ?>>
        <td class="mdl-data-table__cell--non-numeric">{{ $o['o']->name }}</td>
        <td>&times;&nbsp;{{ $o['o']->quantity }}</td>
    </tr>
        @endif
    @endif
@endforeach
</table>

 @stop
