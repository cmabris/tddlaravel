@extends('layout')

@section('title', 'Nuevo usuario')

@section('content')
    <div class="card">
        <div class="card-header h4">
            Crear nuevo usuario
        </div>
        <div class="card-body">
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

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" placeholder="Nombre" value="{{ old('name') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" placeholder="email" value="{{ old('email') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" placeholder="Al menos 6 caracteres" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="bio">Biografía</label>
                        <textarea name="bio" id="bio" class="form-control">{{ old('bio') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="twitter">Twitter</label>
                        <input type="text" class="form-control" name="twitter" id="twitter" value="{{ old('twitter') }}" placeholder="URL de twitter del usuario">
                    </div>
                    <div class="form-group">
                        <label for="profession_id">Profesión</label>
                        <select name="profession_id" id="profession_id" class="form-control">
                            <option value="">Selecciona una opción</option>
                            @foreach($professions as $profession)
                                <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? ' selected' : '' }}>{{ $profession->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h5>Habilidades</h5>

                    @foreach($skills as $skill)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="skills[{{ $skill->id }}]" type="checkbox"
                                   id="skill_{{ $skill->id }}" value="{{ $skill->id }}"
{{--                                   {{ (is_array(old('skills')) && in_array($skill->id, old('skills'))) ? 'checked' : '' }}--}}
                                    {{ old('skills.' . $skill->id) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
                        </div>
                    @endforeach

                    <h5 class="mt-3">Rol</h5>

                    @foreach($roles as $role => $name)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role"
                                   id="role_{{ $role }}" value="{{ $role }}"
                                   {{ old('role') == $role ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="role_{{ $role }}">{{ $name }}</label>
                        </div>
                    @endforeach

                    <div class="form-group mt-4">
                        <button type="submit">Crear usuario</button>
                        <a href="{{ route('users.index') }}" class="btn btn-link">Regresar al listado de usuarios</a>
                    </div>
                </form>
        </div>
    </div>
@endsection
