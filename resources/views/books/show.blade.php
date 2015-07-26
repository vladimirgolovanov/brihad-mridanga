@extends('layouts.master')
 
@section('content')
 
<div class="title">{{ $book->name }}</div>

    <p>
	    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-info">Edit</a>
    </p>
 
@stop