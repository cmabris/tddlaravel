@extends('layout')

@section('title', "Listado usuarios")

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>
        <p>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Nuevo usuario</a>
        </p>
    </div>

<ul>
    @if($users->isNotEmpty())
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td scope="row">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="form-inline">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-link"><span class="material-symbols-outlined">visibility</span></a>
                        <a href="{{ route('user.edit', $user) }}" class="btn btn-link"><span class="material-symbols-outlined">edit</span></a>
                        <form action="{{ route('user.destroy', $user) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-link"><span class="material-symbols-outlined">delete</span></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No hay usuarios registrados</p>
    @endif
</ul>
@endsection
