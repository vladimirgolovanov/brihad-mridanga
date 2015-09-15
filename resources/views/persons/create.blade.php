@extends('layouts.master')
 
@section('content')
 
<h1>Create person</h1>

{!! Form::open([
    'route' => 'persons.store'
]) !!}

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>
<br>
<div class="mdl-textfield mdl-js-textfield textfield-demo">
	@foreach($groups as $group)
	    <label for="group-{{ $group->id }}" class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
	        <input type="radio" class="mdl-radio__button" id="group-{{ $group->id }}" name="group_id" value="{{ $group->id }}" />
	        <span class="mdl-radio__label">{{ $group->name }}</span>
	    </label>
	@endforeach
</div>

<br>

{!! Form::submit('Create New Person', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop
