@extends('layout')

@section('title', 'Editar perfil del usuario')

@section('content')
    @card
    @slot('header', 'Editar perfil')

    @include('shared._errors')

    <form action="{{ url('editar-perfil') }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="first_name">Nombre</label>
            <input type="text" name="first_name" placeholder="Nombre" value="{{ old('first_name', $user->first_name) }}"
                   class="form-control">
        </div>
        <div class="form-group">
            <label for="last_name">Apellidos</label>
            <input type="text" name="last_name" placeholder="Apellidos" value="{{ old('last_name', $user->last_name) }}"
                   class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" placeholder="email" value="{{ old('email', $user->email) }}"
                   class="form-control">
        </div>
        <div class="form-group">
            <label for="bio">Biografía</label>
            <textarea name="bio" id="bio" class="form-control">{{ old('bio', $user->profile->bio) }}</textarea>
        </div>
        <div class="form-group">
            <label for="profession_id">Profesión</label>
            <select name="profession_id" id="profession_id" class="form-control">
                <option value="">Selecciona una opción</option>
                @foreach($professions as $profession)
                    <option value="{{ $profession->id }}" {{ old('profession_id', $user->profile->profession_id) == $profession->id ? ' selected' : '' }}>{{ $profession->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="twitter">Twitter</label>
            <input type="text" class="form-control" name="twitter" id="twitter"
                   value="{{ old('twitter', $user->profile->twitter) }}"
                   placeholder="URL de twitter del usuario">
        </div>

        <div class="form-group mt-4">
            <button type="submit">Actualizar usuario</button>
            <a href="{{ route('users.index') }}" class="btn btn-link">Regresar al listado de usuarios</a>
        </div>
    </form>
    @endcard
@endsection
