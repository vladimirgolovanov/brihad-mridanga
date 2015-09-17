@extends('layouts.master')
 
@section('content')
 
<h1>Books</h1>

@foreach($groups as $group)
    <h2>{{ $group->name }}</h2>
    @foreach($books[$group->id] as $book)
        <p>
            {{ $book->name }}
            <a href="{{ route('books.show', $book->id) }}">View</a>
            <a href="{{ route('books.edit', $book->id) }}">Edit</a>
            <a href="{{ route('newbookprice', ['id'=>$book->id]) }}">Price</a>
        </p>
    @endforeach
@endforeach

<p><a href="{{ route('books.create') }}">Create book</a></p>
<hr>
 
@stop