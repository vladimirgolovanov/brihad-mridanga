@extends('layouts.master')
 
@section('content')
 
<h1>Create book group</h1>

{!! Form::open([
    'route' => 'bookgroups.store'
]) !!}

<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>

{!! Form::submit('Create New Book Group', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop