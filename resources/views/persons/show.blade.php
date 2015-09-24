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
@foreach($operations as $datetime => $operation)
	<tr>
		<td class="mdl-data-table__cell--non-numeric">{{ $operation['data'][0]['nice_date'] }}</td>
		<td class="mdl-data-table__cell--non-numeric" colspan="2">{{ $operation_type_name[$operation['data'][0]['operation_type']] }}</td>
		<td colspan="2"></td>
		<td>
		<?php
			if($operation['data'][0]['operation_type'] == 1) {
				print array_sum($operation['quantity']);
			}
			if($operation['data'][0]['operation_type'] == 2) {
				print '&ndash;'.$operation['data'][0]['laxmi'];
			}
			if($operation['data'][0]['operation_type'] == 4) {
				print '&ndash;'.array_sum($operation['quantity']);
			}
		?>
		</td>
	</tr>
	<?php
		if($operation['data'][0]['operation_type'] == 1) {
			foreach($operation['data'] as $o) {
	?>
	<tr>
	<td></td>
	<td>&nbsp;</td>
	<td class="mdl-data-table__cell--non-numeric">{{ $book_names_by_id[$o['book_id']] }}</td>
	<td class="mdl-data-table__cell--non-numeric">&times;&nbsp;{{ $o['quantity'] }}</td>
	<td>{{ $o['price'] }}</td>
	<td>{{ ($o['quantity']*$o['price']) }}</td>
	</tr>
	<?php
			}
		}
	?>
@endforeach
	<tr>
	<td colspan="4"></td>
	<td class="mdl-data-table__cell--non-numeric">Итого:</td>
	<td>{{ $summ }}</td>
	</tr>
</table>
 
@stop
