@extends('layouts.master')
 
@section('content')
 
<h1>Make operation</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

<input type="hidden" name="datetime" id="datetime" value="{{ $datetime?$datetime:"" }}"/>
<?php /* if(datetime) */ ?>
<p>
<input type="text" name="custom_date" id="custom_date" tabindex="1" value="{{ $custom_date?$custom_date:date("Y-m-d") }}" />
</p>

@if(!$datetime)
<p>
    <a href="{{ route('operation.make'.($shop?'':'_shop'), $personid) }}" class="mdl-button mdl-js-button mdl-button--accent">{{ $shop?'Distribution prices':'Shop prices' }}</a>
</p>
@endif

<?php $popular = 1; $tabindex = 2; ?>
@foreach($books as $book)
<div class="mdl-grid"<?php if(!$book->sorting && $popular) { ?> style="border-bottom:1px solid orange; margin-bottom:3x`0px;"<?php $popular = 0;  } ?>>
    <div class="mdl-cell mdl-cell--4-col  mdl-cell--2-col-phone">
        {!! $book->name !!}
    </div>
    <div class="mdl-cell mdl-cell--1-col">
        {!! Form::input('number', 'bookcount['.$book->id.']', ((isset($editing['bookvalues'][$book->id]))?($editing['bookvalues'][$book->id]):(null)), ['data-step' => $book->pack, 'class' => 'mdl-textfield__input up_down_number', 'id' => 'quantity'.$book->id, 'tabindex' => $tabindex++]) !!}
    </div>
    <div class="mdl-cell mdl-cell--1-col">
        {!! Form::input('text', 'price['.$book->id.']', ((isset($editing['price'][$book->id]))?($editing['price'][$book->id]):($shop?$book->price_shop:$book->price)), ['class' => 'mdl-textfield__input', 'id' => 'price'.$book->id]) !!}
    </div>
</div>
@endforeach

<p>Description: {!! Form::textarea('description', ((isset($editing['description']))?($editing['description']):(null)), ['rows' => 3, 'style' => 'width:100%']) !!}</p>

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br><br>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop