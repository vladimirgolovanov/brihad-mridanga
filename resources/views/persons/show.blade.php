@extends('layouts.master')
 
@section('content')
 
<h1>{{ $person->name }}</h1>

<p><a href="{{ route('persons.edit', $person->id) }}">Edit</a></p>

<table cellpadding="5">
@foreach($operations as $datetime => $operation)
	<tr>
		<td>{{ $operation_type_name[$operation['data'][0]['operation_type']] }}</td>
		<td>{{ $datetime }}</td>
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
@endforeach
</table>

<p>final summ: {{ $summ }}</p>

<p><a href="{{ route('operation.make', $person->id) }}">Operation 1: get books</a></p>
<p><a href="{{ route('operation.laxmi', $person->id) }}">Operation 2: laxmi</a></p>
<p><a href="{{ route('operation.remain', $person->id) }}">Operation 3: remain</a></p>
<p><a href="{{ route('operation.return', $person->id) }}">Operation 4: return</a></p>
 
@stop
