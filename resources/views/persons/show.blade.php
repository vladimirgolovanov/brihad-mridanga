@extends('layouts.master')
 
@section('content')
 
<h1>{{ $person->name }}</h1>

<p><a href="{{ route('persons.edit', $person->id) }}">Edit</a></p>

<table>
@foreach($operations as $datetime => $operation)
	<tr>
		<td>{{ $datetime }}</td>
		<td>
		{{ array_sum($operation['quantity']) }}
		</td>
	</tr>
@endforeach
</table>

<p>final summ: {{ $summ }}</p>
 
@stop
