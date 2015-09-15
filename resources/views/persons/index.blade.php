@extends('layouts.master')
 
@section('content')
 
<h1>Persons</h1>

@foreach($groups as $group)
    <h2>{{ $group->name }}</h2>
    @foreach($persons[$group->id] as $person)
        <p>
            {{ $person->name }}
            <a href="{{ route('persons.show', $person->id) }}">View</a>
            <a href="{{ route('persons.edit', $person->id) }}">Edit</a>
        </p>
    @endforeach
@endforeach

<p><a href="{{ route('persons.create') }}">Create person</a></p>
<hr>
 
@stop
