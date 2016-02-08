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

<p>
    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="hide">
        <input type="checkbox" name="hide" id="hide" class="mdl-switch__input" <?php if($person->hide) print ' checked'; ?> />
        <span class="mdl-switch__label">Hide</span>
    </label>
</p>

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