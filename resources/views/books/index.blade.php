@extends('layouts.master')
 
@section('content')
 
<h1>Books</h1>

<p><a href="{{ route('books.create') }}">Create book</a></p>

@foreach($books as $book)
    <p>
        <a href="{{ route('books.edit', $book->id) }}">{{ $book->name }}</a>
    </p>
@endforeach

<hr>

@stop