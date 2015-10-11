@extends('layouts.master')
 
@section('content')
 
<h1>{{ $person->name }}</h1>

<p>{{ array_keys($operations)[0] }}</p>

<p>{{ $operation_type_name[$operations[array_keys($operations)[0]]['data'][0]['operation_type']] }}</p>
<p>Сумма: {{ $summ }}</p>

<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
@foreach($operations as $datetime => $operation)
	<?php
		if($operation['data'][0]['operation_type'] == 1) {
			foreach($operation['data'] as $o) {
	?>
	<tr>
	<td class="mdl-data-table__cell--non-numeric">{{ $book_names_by_id[$o['book_id']] }}</td>
	<td class="mdl-data-table__cell--non-numeric">&times;&nbsp;{{ $o['quantity'] }}</td>
	<td>{{ $o['price'] }}</td>
	<td>{{ ($o['quantity']*$o['price']) }}</td>
	<td><a href="{{ route('persons.edit.operation', ['personid' => $person['id'], 'operationid' => $datetime, 'bookid' => $o['book_id']]) }}">edit</a></td>
	</tr>
	<?php
			}
		} elseif($operation['data'][0]['operation_type'] == 3) {
			foreach($operation['data'] as $o) {
	?>
	<tr>
	<td class="mdl-data-table__cell--non-numeric">{{ $book_names_by_id[$o['book_id']] }}</td>
	<td class="mdl-data-table__cell--non-numeric">&times;&nbsp;{{ $o['quantity'] }}</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><a href="{{ route('persons.edit.operation', ['personid' => $person['id'], 'operationid' => $datetime, 'bookid' => $o['book_id']]) }}">edit</a></td>
	</tr>
	<?php
			}
		} elseif($operation['data'][0]['operation_type'] == 4) {
			foreach($operation['data'] as $o) {
	?>
	<tr>
	<td class="mdl-data-table__cell--non-numeric">{{ $book_names_by_id[$o['book_id']] }}</td>
	<td class="mdl-data-table__cell--non-numeric">&times;&nbsp;{{ $o['quantity'] }}</td>
	<td colspan="2">&nbsp;</td>
	<td><a href="">edit</a></td>
	</tr>
	<?php
			}
		}
	?>
@endforeach
</table>

@stop
