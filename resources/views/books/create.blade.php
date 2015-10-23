@extends('layouts.master')
 
@section('content')
 
<h1>Create book</h1>

{!! Form::open([
    'route' => 'books.store'
]) !!}

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('shortname', 'Short name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('shortname', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('pack', 'Pack:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('pack', 1, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('bookprice', 'Price:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('bookprice', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

{!! Form::submit('Create New Book', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop