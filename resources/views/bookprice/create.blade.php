@extends('layouts.master')
 
@section('content')
 
<h1>Update book price</h1>
<p>{!! $bookname !!}</p>

{!! Form::open([
    'route' => 'bookprice.store'
]) !!}

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('bookprice', 'Price:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('bookprice', $bookprice, ['class' => 'mdl-textfield__input']) !!}
</div>

{!! Form::hidden('bookid', $bookid) !!}

<br>

{!! Form::submit('Update Price', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop