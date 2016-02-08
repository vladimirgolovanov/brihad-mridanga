@extends('layouts.master')
 
@section('content')
 
<h1>Persons</h1>

<p><a href="{{ route('persons.create') }}">Create person</a></p>
<hr>

@foreach($persons as $person)
    <p>
        <a href="{{ route('persons.show', $person->id) }}" style="color:{{ $person->hide?'#999':'black' }};">{{ $person->name }}</a>
        <a href="{{ route('persons.edit', $person->id) }}"<?php print $person->hide?' style="color:#999;"':''; ?>>Edit</a>
        {{ $person->last_remains_date }}
    </p>
@endforeach

@stop
