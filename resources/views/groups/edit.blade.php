@extends('layouts.master')
 
@section('content')
 
<h1>Edit {{ $group->name }}</h1>

{!! Form::model($group, [
    'method' => 'PATCH',
    'route' => ['groups.update', $group->id]
]) !!}

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>

{!! Form::submit('Update', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

{!! Form::open([
    'method' => 'DELETE',
    'route' => ['groups.destroy', $group->id]
]) !!}
{!! Form::submit('Delete group?', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
{!! Form::close() !!}

@stop