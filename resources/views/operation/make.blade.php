@extends('layouts.master')
 
@section('content')
 
<h1>Make operation</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

<?php /* if(datetime) */ ?>
<p>
<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="custom_date_switch">
  <input type="checkbox" name="custom_date_switch" id="custom_date_switch" class="mdl-switch__input" <?php if($datetime) print ' checked'; ?> />
  <span class="mdl-switch__label">Custom date</span>
</label><br><br>
<input type="text" name="custom_date" id="custom_date" <?php if($datetime) print ' value="'.$datetime.'"'; else print ' disabled style="display:none;"'; ?> placeholder="YYYY-MM-DD" />
</p>

@foreach($books as $book)
    <p>
        <div style="width:50%;float:left;">
            {!! $book->shortname or $book->name !!}
        </div>
        <div style="float:left;">
            <span class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored stepqty" field="quantity{{ $book->id }}" id="less{{ $book->id }}" data-move="less"><i class="material-icons">arrow_drop_down</i></span>{!! Form::input('number', 'bookcount['.$book->id.']', ((isset($editing['bookvalues'][$book->id]))?($editing['bookvalues'][$book->id]):(null)), ['data-step' => $book->pack, 'class' => 'mdl-textfield__input up_down_number', 'id' => 'quantity'.$book->id]) !!}<span class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored stepqty" field="quantity{{ $book->id }}" id="more{{ $book->id }}" data-move="more"><i class="material-icons">arrow_drop_up</i></span>
        </div>
        <div style="clear:both;"></div>
    </p>
@endforeach

<p>Description: {!! Form::textarea('description', ((isset($editing['description']))?($editing['description']):(null)), ['rows' => 3, 'style' => 'width:100%']) !!}</p>

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br><br>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop