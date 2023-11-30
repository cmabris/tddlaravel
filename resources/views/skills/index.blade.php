@extends('layout')

@section('title', 'Habilidades')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">Listado de habilidades</h1>
    </div>

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Título</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($skills as $skill)
            <tr>
                <td scope="row">{{ $skill->id }}</td>
                <td>{{ $skill->name }}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
