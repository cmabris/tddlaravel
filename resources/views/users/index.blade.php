@extends('layout')

@section('title', "Listado usuarios")

@section('content')
<h1>{{ $title }}</h1>

<ul>
    @forelse($users as $user)
        <li>{{ $user->name }} ({{ $user->email }})</li>
    @empty
        <p>No hay usuarios registrados</p>
    @endforelse
</ul>
@endsection
