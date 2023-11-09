@extends('layout')

@section('title', "Usuario {$user->id}")

@section('content')
    <h1>Usuario #{{ $user->id }}</h1>

    <p>Nombre del usuario: {{ $user->name }}</p>
    <p>Correo electrónico: {{ $user->email }}</p>

    <p>
        <a href="{{ url('usuarios') }}">Regresar al listado de usuarios</a>
    </p>
    <p>
        <a href="{{ action('UserController@index') }}">Regresar</a>
    </p>
    <p>
        <a href="{{ route('users.index') }}">Regresar al listado</a>
    </p>
@endsection