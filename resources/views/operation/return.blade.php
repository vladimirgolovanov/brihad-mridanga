@extends('layouts.master')
 
@section('content')
 
<h1>Return books</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

<input type="hidden" name="datetime" id="datetime" value="{{ $datetime?$datetime:"" }}"/>
<?php /* if(datetime) */ ?>
<p>
    <input type="text" name="custom_date" id="custom_date" tabindex="1" value="{{ $custom_date?$custom_date:date("Y-m-d") }}" />
</p>

@foreach($books as $book)
	<p>{!! $book->name !!}
        {!! Form::text('bookcount['.$book->id.']', ((isset($editing['bookvalues'][$book->id]))?($editing['bookvalues'][$book->id]):(null)), ['class' => 'mdl-textfield__input']) !!}
        {!! Form::hidden('price['.$book->id.']', $book->price) !!}
    </p>
@endforeach

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br>

<p>Description: {!! Form::textarea('description', ((isset($editing['description']))?($editing['description']):(null)), ['rows' => 3, 'style' => 'width:100%']) !!}</p>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop