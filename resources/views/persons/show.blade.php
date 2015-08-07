@extends('layouts.master')
 
@section('content')
 
<h1>{{ $person->name }}</h1>

<p>
    <a href="{{ route('persons.edit', $person->id) }}">Edit</a>
</p>
 
@stop
