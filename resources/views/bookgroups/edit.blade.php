@extends('layouts.master')
 
@section('content')
 
<h1>Edit {{ $bookgroup->name }}</h1>

{!! Form::model($bookgroup, [
    'method' => 'PATCH',
    'route' => ['bookgroups.update', $bookgroup->id]
]) !!}

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

{!! Form::submit('Update', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}
<!--
{!! Form::open([
    'method' => 'DELETE',
    'route' => ['bookgroups.destroy', $bookgroup->id]
]) !!}
{!! Form::submit('Delete book group?', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
{!! Form::close() !!}
-->
@stop