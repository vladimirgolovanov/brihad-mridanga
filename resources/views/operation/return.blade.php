@extends('layouts.master')
 
@section('content')
 
<h1>Return books</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

<input type="hidden" name="datetime" id="datetime" value="{{ $datetime?$datetime:"" }}"/>
<?php /* if(datetime) */ ?>
<p>
<input type="text" name="custom_date" id="custom_date" tabindex="1" value="{{ $custom_date?$custom_date:date("Y-m-d") }}" />
&nbsp;&nbsp;&nbsp;<a href="#" onclick="$('#custom_date').val($(this).text());" style="text-decoration:none;color:#999;border-bottom:dotted 1px;">{{ session('custom_date') }}</a>
</p>

@foreach($books as $book)
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--4-col  mdl-cell--2-col-phone">
            {!! $book->name !!}<br><span class="mdl-typography--caption" style="color:#999;">{!! $book->bookgroup_name !!}{!! $book->shortname?" | ".$book->shortname:"" !!}</span>
        </div>
        <div class="mdl-cell mdl-cell--1-col">
            {!! Form::input('number', 'bookcount['.$book->id.']', ((isset($editing['bookvalues'][$book->id]))?($editing['bookvalues'][$book->id]):(null)), ['data-step' => $book->pack, 'class' => 'mdl-textfield__input up_down_number', 'id' => 'quantity'.$book->id]) !!}
            {!! Form::hidden('price['.$book->id.']', $book->price) !!}
        </div>
        @if(!$datetime)
            <div class="mdl-cell mdl-cell--1-col">
                <a href="#" onclick="$('#quantity{{ $book->id }}').val($(this).text());" style="text-decoration:none;color:#999;border-bottom:dotted 1px;">{{ $book->havegot }}</a>
            </div>
        @endif
    </div>
@endforeach

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br>

<p>Description: {!! Form::textarea('description', ((isset($editing['description']))?($editing['description']):(null)), ['rows' => 3, 'style' => 'width:100%']) !!}</p>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop