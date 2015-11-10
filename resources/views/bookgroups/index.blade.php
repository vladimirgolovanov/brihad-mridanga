@extends('layouts.master')
 
@section('content')
 
<h1>Books</h1>

<p><a href="{{ route('bookgroups.create') }}">Create book group</a></p>

@foreach($bookgroups as $bg)
    <p>
        <a href="{{ route('bookgroups.edit', $bg->id) }}">{{ $bg->name }}</a>
    </p>
@endforeach

<hr>

@stop