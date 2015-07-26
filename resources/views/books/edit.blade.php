@extends('layouts.master')
 
@section('content')
 
<div class="title">Edit {{ $book->name }}</div>

{!! Form::model($book, [
    'method' => 'PATCH',
    'route' => ['books.update', $book->id]
]) !!}

<div class="form-group">
    {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

{!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
 
{!! Form::close() !!}

{!! Form::open([
    'method' => 'DELETE',
    'route' => ['books.destroy', $book->id]
]) !!}
{!! Form::submit('Delete book?', ['class' => 'btn btn-danger']) !!}
{!! Form::close() !!}

    <p>
	    <a href="{{ route('books.index') }}" class="btn btn-info">All books</a>
    </p>
 
@stop