@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')
    <h1>Nuevo usuario</h1>

    <form class="panel" method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <label for="name">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>

        <label for="password">Contrasena</label>
        <input id="password" name="password" type="password" required>

        <label for="role">Rol</label>
        <select id="role" name="role" required>
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
            @endforeach
        </select>

        <button class="btn" type="submit">Guardar usuario</button>
        <a class="btn secondary" href="{{ route('usuarios.index') }}">Volver</a>
    </form>
@endsection
