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
@foreach($milestones as $milestone)
	<?php if(isset($next) and count($next)) { ?>
		<tr>
			<td class="mdl-data-table__cell--non-numeric" colspan="2" style="background:#ccc;">
				<a href="{{ route('persons.operation', [$person->id, $next[0]['datetime']]) }}">{{ $next[0]['nice_date'] }}</a>,
				{{ $operation_type_name[3] }}
			</td>
		</tr>
	<?php foreach($next as $o) { ?>
		<tr>
		<td class="mdl-data-table__cell--non-numeric" style="background:#ccc;">{{ $book_names_by_id[$o['book_id']] }}</td>
		<td class="mdl-data-table__cell--non-numeric" style="background:#ccc;">&times;&nbsp;{{ $o['quantity'] }}</td>
		</tr>
	<?php } ?>
	<?php } ?>
<?php if(isset($operations[$milestone[0]])) { ?>
	<tr>
	<td class="mdl-data-table__cell--non-numeric" style="background:#ccc;border-top:#999 3px solid;">Итого:</td>
	<td style="background:#ccc;border-top:#999 3px solid;">{{ $summ[$milestone[0]] }}</td>
	</tr>
@foreach($operations[$milestone[0]] as $datetime => $operation)
	<tr>
		<td class="mdl-data-table__cell--non-numeric" colspan="2">
			<a href="{{ route('persons.operation', [$person->id, $datetime]) }}">{{ $operation['data'][0]['nice_date'] }}</a>,
			{{ $operation_type_name[$operation['data'][0]['operation_type']] }}
			<p style="color:#666;">{{ $operation['description'] }}</p>
		</td>
	</tr>
	<?php
		if($operation['data'][0]['operation_type'] == 1) {
			foreach($operation['data'] as $o) {
	?>
	<tr>
	<td class="mdl-data-table__cell--non-numeric">{{ $book_names_by_id[$o['book_id']] }}</td>
	<td class="mdl-data-table__cell--non-numeric">&times;&nbsp;{{ $o['quantity'] }}</td>
	</tr>
	<?php
			}
		} elseif($operation['data'][0]['operation_type'] == 2) {
	?>
	<tr>
	<td colspan="2" class="mdl-data-table__cell">{{ $operation['data'][0]['laxmi'] }}</td>
	</tr>
	<?php
		} elseif($operation['data'][0]['operation_type'] == 3) {
			foreach($operation['data'] as $o) {
	?>
	<tr>
	<td class="mdl-data-table__cell--non-numeric">{{ $book_names_by_id[$o['book_id']] }}</td>
	<td class="mdl-data-table__cell--non-numeric">&times;&nbsp;{{ $o['quantity'] }} ({{ $books[$o['book_id']] }})</td>
	</tr>
	<?php
			}
		} elseif($operation['data'][0]['operation_type'] == 4) {
			foreach($operation['data'] as $o) {
	?>
	<tr>
	<td class="mdl-data-table__cell--non-numeric">&ndash; {{ $book_names_by_id[$o['book_id']] }}</td>
	<td class="mdl-data-table__cell--non-numeric">&times;&nbsp;{{ $o['quantity'] }}</td>
	</tr>
	<?php
			}
		}
	?>
@endforeach
<?php } ?>
<?php $next = $milestonedata[$milestone[0]]; ?>
@endforeach
</table>
 @stop
