@extends('layouts.master')
 
@section('content')
 
<h1>Edit {{ $book->name }}</h1>

{!! Form::model($book, [
    'method' => 'PATCH',
    'route' => ['books.update', $book->id]
]) !!}

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('shortname', 'Short name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('shortname', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('pack', 'Pack:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('pack', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('price_buy', 'Buying price:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('price_buy', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('price', 'Distr price:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('price', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('price_shop', 'Shop price:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('price_shop', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

{!! Form::submit('Update', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

{!! Form::open([
    'method' => 'DELETE',
    'route' => ['books.destroy', $book->id]
]) !!}
{!! Form::submit('Delete book?', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
{!! Form::close() !!}

@stop