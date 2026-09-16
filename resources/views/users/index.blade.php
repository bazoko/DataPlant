@extends('layouts.app')

@section('title', 'Usuarios | DataPlant')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Administracion</p>
            <h1>Usuarios y permisos</h1>
            <p>Consulta de las personas y roles registrados en DataPlant.</p>
        </div>
        <a class="button secondary" href="{{ route('dashboard') }}">Volver al dashboard</a>
    </div>

    <section class="panel">
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Roles</th>
                    <th>Alta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class="role">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $user->created_at?->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
