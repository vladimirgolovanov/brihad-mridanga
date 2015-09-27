@extends('layouts.master')
 
@section('content')
 
<h1>Groups</h1>

@foreach($groups as $group)
    <p>
	    {{ $group->name }}
	    <a href="{{ route('groups.show', $group->id) }}">View</a>
	    <a href="{{ route('groups.edit', $group->id) }}">Edit</a>
    </p>
@endforeach

<p><a href="{{ route('groups.create') }}">Create group</a></p>
<hr>
 
@stop