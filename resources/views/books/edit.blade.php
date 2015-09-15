@extends('layouts.master')
 
@section('content')
 
<h1>Edit {{ $book->name }}</h1>

{!! Form::model($book, [
    'method' => 'PATCH',
    'route' => ['books.update', $book->id]
]) !!}

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('name', 'Name:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('name', null, ['class' => 'mdl-textfield__input']) !!}
</div>

<br>
<div class="mdl-textfield mdl-js-textfield textfield-demo">
    @foreach($groups as $group)
        <label for="group-{{ $group->id }}" class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
            {!! Form::radio('group_id', $group->id, $group->id==$book->group_id, ['class'=>'mdl-radio__button', 'id'=>'group-'.$group->id]) !!}
            <span class="mdl-radio__label">{{ $group->name }}</span>
        </label>
    @endforeach
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