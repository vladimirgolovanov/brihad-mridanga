@extends('layouts.master')
 
@section('content')
 
<h1>Make operation</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

@foreach($books as $book)
    <p>
        <div style="width:50%;float:left;">
            {!! $book->shortname or $book->name !!}
        </div>
        <div style="float:left;">
            <span class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored stepqty" field="quantity{{ $book->id }}" id="less{{ $book->id }}" data-move="less"><i class="material-icons">arrow_drop_down</i></span>{!! Form::input('number', 'bookcount['.$book->id.']', null, ['data-step' => $book->pack, 'class' => 'mdl-textfield__input up_down_number', 'id' => 'quantity'.$book->id]) !!}<span class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored stepqty" field="quantity{{ $book->id }}" id="more{{ $book->id }}" data-move="more"><i class="material-icons">arrow_drop_up</i></span>
        </div>
        <div style="clear:both;"></div>
    </p>
@endforeach

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop