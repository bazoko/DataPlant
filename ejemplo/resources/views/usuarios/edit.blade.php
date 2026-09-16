@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
    <h1>Editar usuario</h1>

    <form class="panel" method="POST" action="{{ route('usuarios.update', $usuario) }}">
        @csrf
        @method('PUT')

        <label for="name">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name', $usuario->name) }}" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" required>

        <label for="password">Nueva contrasena</label>
        <input id="password" name="password" type="password">
        <p class="muted">Dejar vacio para mantener la contrasena actual.</p>

        <label for="role">Rol</label>
        <select id="role" name="role" required>
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(old('role', $usuario->role) === $role)>{{ $role }}</option>
            @endforeach
        </select>

        <button class="btn" type="submit">Actualizar usuario</button>
        <a class="btn secondary" href="{{ route('usuarios.index') }}">Volver</a>
    </form>
@endsection
