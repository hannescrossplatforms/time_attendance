@extends('layout')

@section('content')
  <h2>Welcome "{{ Auth::user()->username }}" to the protected page!</h2>
  <p>Your user ID is: {{ Auth::user()->id }}</p>

  <h3>Users:<h3>
  	@foreach($users as $user)
        <p>{{ $user->id }}</p>
    @endforeach
@stop