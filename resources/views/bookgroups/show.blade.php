@extends('layouts.master')
 
@section('content')
 
<h1>{{ $bookgroup->name }}</h1>

<p><a href="{{ route('bookgroups.edit', $bookgroup->id) }}">Edit</a></p>
 
@stop