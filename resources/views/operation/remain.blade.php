@extends('layouts.master')
 
@section('content')
 
<h1>Fill remains</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

@foreach($books as $book)
	<p>{!! $book->name !!} {!! Form::text('bookcount['.$book->id.']', null, ['class' => 'mdl-textfield__input']) !!}</p>
@endforeach

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop