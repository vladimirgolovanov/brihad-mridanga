@extends('layouts.master')
 
@section('content')
 
<h1>{{ $book->name }}</h1>

<p>
    <a href="{{ route('books.edit', $book->id) }}">Edit</a>
</p>
 
@stop