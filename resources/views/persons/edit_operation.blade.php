@extends('layouts.master')
 
@section('content')
 
<h1>Edit {{ $operation->datetime }}</h1>

{!! Form::model($operation, [
    'method' => 'PATCH',
    'route' => ['persons.operation.store', 'operationid' => $operation->datetime, 'personid' => $operation->person_id, 'bookid' => $operation->book_id]
]) !!}

<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('datetime', 'DateTime:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('datetime', null, ['class' => 'mdl-textfield__input']) !!}
</div>
<br>
<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('quantity', 'Quantity:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('quantity', null, ['class' => 'mdl-textfield__input']) !!}
</div>
<br>
<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('operation_type', 'Operation Type:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('operation_type', null, ['class' => 'mdl-textfield__input']) !!}
</div>
<br>
<div class="mdl-textfield mdl-js-textfield textfield-demo">
    {!! Form::label('laxmi', 'Laxmi:', ['class' => 'mdl-textfield__label']) !!}
    {!! Form::text('laxmi', null, ['class' => 'mdl-textfield__input']) !!}
</div>
<br>

{!! Form::submit('Update', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

{!! Form::open([
    'method' => 'DELETE',
    'route' => ['persons.operation.delete', 'operationid' => $operation->datetime, 'personid' => $operation->person_id, 'bookid' => $operation->book_id]
]) !!}
{!! Form::submit('Delete operation?', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
{!! Form::close() !!}

@stop