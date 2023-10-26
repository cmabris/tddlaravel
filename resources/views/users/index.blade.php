@extends('layout')

@section('title', "Listado usuarios")

@section('content')
<h1>{{ $title }}</h1>

<ul>
    @forelse($users as $user)
        <li>{{ $user }}</li>
    @empty
        <p>No hay usuarios registrados</p>
    @endforelse
</ul>
@endsection

@section('sidebar')
    <h2>Barra Personalizada</h2>
@endsection