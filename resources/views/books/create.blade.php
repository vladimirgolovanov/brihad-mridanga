@extends('layouts.master')
 
@section('content')
 
<div class="title">Create book</div>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores, possimus, ullam? Deleniti dicta eaque facere, facilis in inventore mollitia officiis porro totam voluptatibus! Adipisci autem cumque enim explicabo, iusto sequi.</p>
<hr>

{!! Form::open([
    'route' => 'books.store'
]) !!}
 
<div class="form-group">
    {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
    <br />
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
 
{!! Form::submit('Create New Book', ['class' => 'btn btn-primary']) !!}
 
{!! Form::close() !!}

@stop