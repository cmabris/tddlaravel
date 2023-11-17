@extends('layout')

@section('title', 'Nuevo usuario')

@section('content')
    <h1>Crear nuevo usuario</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <h6>Por favor, corrige los siguientes errores</h6>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.store') }}" method="POST">
        {{ csrf_field() }}

        <label for="name">Nombre</label>
        <input type="text" name="name" placeholder="Nombre" value="{{ old('name') }}">
        @if($errors->has('name'))
            <p>{{ $errors->first('name') }}</p>
        @endif
        <br>
        <label for="email">Correo Electrónico</label>
        <input type="email" name="email" placeholder="email" value="{{ old('email') }}">
        @if($errors->has('email'))
            <p>{{ $errors->first('name') }}</p>
        @endif
        <br>
        <label for="password">Contraseña</label>
        <input type="password" name="password" placeholder="Al menos 6 caracteres">

        <button type="submit">Crear usuario</button>
    </form>

    <p>
        <a href="{{ route('users.index') }}">Regresar al listado de usuarios</a>
    </p>
@endsection
