@extends('layout')

@section('title', 'Nuevo usuario')

@section('content')
    <h1>Crear nuevo usuario</h1>

    <form action="{{ route('user.store') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit">Crear usuario</button>
    </form>

    <p>
        <a href="{{ route('users.index') }}">Regresar al listado de usuarios</a>
    </p>
@endsection
