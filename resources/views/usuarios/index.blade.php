@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <h1>Usuarios</h1>

    <div class="actions">
        <a class="btn" href="{{ route('usuarios.create') }}">Nuevo usuario</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->role }}</td>
                    <td>
                        <a class="btn secondary" href="{{ route('usuarios.edit', $usuario) }}">Editar</a>
                        @if ($usuario->id !== auth()->id())
                            <form class="inline-form" method="POST" action="{{ route('usuarios.destroy', $usuario) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger" type="submit">Eliminar</button>
                            </form>
                        @else
                            <span class="muted">Usuario actual</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Todavia no hay usuarios cargados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
