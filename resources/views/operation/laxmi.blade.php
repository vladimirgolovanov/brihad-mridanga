@extends('layouts.master')
 
@section('content')
 
<h1>Back laxmi</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

<input type="hidden" name="datetime" id="datetime" value="{{ $datetime?$datetime:"" }}"/>
<?php /* if(datetime) */ ?>
<p>
    <input type="text" name="custom_date" id="custom_date" value="{{ $custom_date?$custom_date:date("Y-m-d") }}" />
</p>

<p>Amount: {!! Form::text('laxmi', ((isset($editing['laxmi']))?($editing['laxmi']):(null)), ['class' => 'mdl-textfield__input']) !!}</p>

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br>

<p>Description: {!! Form::textarea('description', ((isset($editing['description']))?($editing['description']):(null)), ['rows' => 3, 'style' => 'width:100%']) !!}</p>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop