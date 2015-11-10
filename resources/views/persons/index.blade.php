@extends('layouts.master')
 
@section('content')
 
<h1>Persons</h1>

@foreach($persons as $person)
    <p>
        {{ $person->name }}
        <a href="{{ route('persons.show', $person->id) }}">View</a>
        <a href="{{ route('persons.edit', $person->id) }}">Edit</a>
        {{ $person->last_remains_date }}
    </p>
@endforeach

<p><a href="{{ route('persons.create') }}">Create person</a></p>
<hr>
 
@stop
