@extends('layouts.master')
 
@section('content')
 
<h1>{{ $group->name }}</h1>

<p>
    <a href="{{ route('groups.edit', $group->id) }}">Edit</a>
</p>
 
@stop