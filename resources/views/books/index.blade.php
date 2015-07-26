@extends('layouts.master')
 
@section('content')
 
<div class="title">Welcome Home</div>

@foreach($books as $book)
    <p>
	    {{ $book->name }}
	    <a href="{{ route('books.show', $book->id) }}" class="btn btn-info">View</a>
	    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-info">Edit</a>
    </p>
@endforeach

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores, possimus, ullam? Deleniti dicta eaque facere, facilis in inventore mollitia officiis porro totam voluptatibus! Adipisci autem cumque enim explicabo, iusto sequi.</p>
<hr>
 
@stop