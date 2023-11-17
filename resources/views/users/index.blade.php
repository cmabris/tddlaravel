@extends('layout')

@section('title', "Listado usuarios")

@section('content')
<h1>{{ $title }}</h1>
<p>
    <a href="{{ route('users.create') }}">Nuevo usuario</a>
</p>
<ul>
    @forelse($users as $user)
        <li>
            {{ $user->name }} ({{ $user->email }})
            <a href="{{ route('users.show', $user) }}">Ver Detalles</a>
            <a href="{{ route('user.edit', $user) }}">Editar </a>
            <form action="{{ route('user.destroy', $user) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit">Eliminar</button>
            </form>
        </li>
    @empty
        <p>No hay usuarios registrados</p>
    @endforelse
</ul>
@endsection
