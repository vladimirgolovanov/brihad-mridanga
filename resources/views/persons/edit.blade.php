@extends('layouts.master')
 
@section('content')
 
<h1>Edit {{ $person->name }}</h1>

{!! Form::model($person, [
    'method' => 'PATCH',
    'route' => ['persons.update', $person->id]
]) !!}

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>
<div class="mdl-textfield mdl-js-textfield textfield-demo">
    @foreach($groups as $group)
        <label for="group-{{ $group->id }}" class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
            {!! Form::radio('group_id', $group->id, $group->id==$person->group_id, ['class'=>'mdl-radio__button', 'id'=>'group-'.$group->id]) !!}
            <span class="mdl-radio__label">{{ $group->name }}</span>
        </label>
    @endforeach
</div>

<br>

{!! Form::submit('Update', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

{!! Form::open([
    'method' => 'DELETE',
    'route' => ['persons.destroy', $person->id]
]) !!}
{!! Form::submit('Delete person?', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
{!! Form::close() !!}

@stop