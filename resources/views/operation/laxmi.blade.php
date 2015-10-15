@extends('layouts.master')
 
@section('content')
 
<h1>Back laxmi</h1>

{!! Form::open([
    'route' => 'operation.store'
]) !!}

<p>
<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="custom_date_switch">
  <input type="checkbox" name="custom_date_switch" id="custom_date_switch" class="mdl-switch__input"  />
  <span class="mdl-switch__label">Custom date</span>
</label><br><br>
<input type="text" name="custom_date" id="custom_date" disabled style="display:none;" placeholder="YYYY-MM-DD" />
</p>

<p>Amount: {!! Form::text('laxmi', null, ['class' => 'mdl-textfield__input']) !!}</p>

{!! Form::hidden('personid', $personid, ['class' => 'mdl-textfield__input']) !!}
{!! Form::hidden('operation_type', $operation_type, ['class' => 'mdl-textfield__input']) !!}

<br>

<p>Description: {!! Form::textarea('description', null, ['rows' => 3, 'style' => 'width:100%']) !!}</p>

{!! Form::submit('Operate', ['class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
 
{!! Form::close() !!}

@stop